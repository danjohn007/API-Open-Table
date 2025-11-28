<?php
/**
 * Controlador para Clientes (Frontend de Reservaciones)
 */

class BookingController extends Controller {
    private $restaurantModel;
    private $tableModel;
    private $reservationModel;
    private $customerModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        
        $this->restaurantModel = new RestaurantModel();
        $this->tableModel = new TableModel();
        $this->reservationModel = new ReservationModel();
        $this->customerModel = new CustomerModel();
    }
    
    /**
     * Página de búsqueda de disponibilidad
     */
    public function index() {
        $restaurants = $this->restaurantModel->getActive();
        
        $this->render('client/booking/search', [
            'restaurants' => $restaurants
        ], 'client');
    }
    
    /**
     * Buscar disponibilidad
     */
    public function search() {
        $restaurantId = $this->getQuery('restaurant_id');
        $date = $this->getQuery('date');
        $time = $this->getQuery('time');
        $partySize = $this->getQuery('party_size', 2);
        
        if (!$restaurantId || !$date) {
            $this->redirect('reservar');
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        // Obtener horario del día
        $dayOfWeek = date('w', strtotime($date));
        $schedules = $this->restaurantModel->getSchedules($restaurantId);
        $daySchedule = null;
        
        foreach ($schedules as $schedule) {
            if ($schedule['day_of_week'] == $dayOfWeek) {
                $daySchedule = $schedule;
                break;
            }
        }
        
        $isClosed = !$daySchedule || $daySchedule['is_closed'];
        
        // Generar slots de tiempo disponibles
        $slots = [];
        
        if (!$isClosed) {
            $openTime = $daySchedule['opening_time'] ?? $restaurant['opening_time'];
            $closeTime = $daySchedule['closing_time'] ?? $restaurant['closing_time'];
            $duration = $restaurant['average_time_per_table'];
            
            $currentTime = strtotime($date . ' ' . $openTime);
            $endTime = strtotime($date . ' ' . $closeTime) - ($duration * 60);
            
            while ($currentTime <= $endTime) {
                $timeStr = date('H:i:s', $currentTime);
                $tables = $this->tableModel->getAvailable($restaurantId, $date, $timeStr, $partySize, $duration);
                
                if (count($tables) > 0) {
                    $slots[] = [
                        'time' => date('H:i', $currentTime),
                        'available_tables' => count($tables)
                    ];
                }
                
                $currentTime += 30 * 60;
            }
        }
        
        $this->render('client/booking/results', [
            'restaurant' => $restaurant,
            'date' => $date,
            'time' => $time,
            'partySize' => $partySize,
            'slots' => $slots,
            'isClosed' => $isClosed
        ], 'client');
    }
    
    /**
     * Formulario de reservación
     */
    public function form() {
        $restaurantId = $this->getQuery('restaurant_id');
        $date = $this->getQuery('date');
        $time = $this->getQuery('time');
        $partySize = $this->getQuery('party_size', 2);
        
        if (!$restaurantId || !$date || !$time) {
            $this->redirect('reservar');
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        $areas = $this->restaurantModel->getAreas($restaurantId);
        
        $this->render('client/booking/form', [
            'restaurant' => $restaurant,
            'date' => $date,
            'time' => $time,
            'partySize' => $partySize,
            'areas' => $areas
        ], 'client');
    }
    
    /**
     * Procesar reservación
     */
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('reservar');
        }
        
        $restaurantId = $this->getPost('restaurant_id');
        $date = $this->getPost('date');
        $time = $this->getPost('time');
        $partySize = $this->getPost('party_size');
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        // Crear o buscar cliente
        $customerId = $this->customerModel->findOrCreate([
            'first_name' => $this->getPost('first_name'),
            'last_name' => $this->getPost('last_name'),
            'email' => $this->getPost('email'),
            'phone' => $this->getPost('phone')
        ]);
        
        // Buscar mesa disponible
        $tables = $this->tableModel->getAvailable(
            $restaurantId, 
            $date, 
            $time . ':00', 
            $partySize, 
            $restaurant['average_time_per_table']
        );
        
        if (count($tables) === 0) {
            $this->setFlash('error', 'Lo sentimos, no hay mesas disponibles para el horario seleccionado');
            $this->redirect('reservar/buscar?restaurant_id=' . $restaurantId . '&date=' . $date . '&party_size=' . $partySize);
            return;
        }
        
        // Asignar la primera mesa disponible
        $tableId = $tables[0]['id'];
        
        // Crear reservación
        $data = [
            'restaurant_id' => $restaurantId,
            'customer_id' => $customerId,
            'table_id' => $tableId,
            'reservation_date' => $date,
            'reservation_time' => $time . ':00',
            'party_size' => $partySize,
            'duration_minutes' => $restaurant['average_time_per_table'],
            'status' => 'confirmed',
            'source' => 'website',
            'special_requests' => $this->getPost('special_requests'),
            'occasion' => $this->getPost('occasion'),
            'area_preference' => $this->getPost('area_preference')
        ];
        
        $reservationId = $this->reservationModel->createReservation($data);
        $reservation = $this->reservationModel->find($reservationId);
        
        // Redirigir a confirmación
        $this->redirect('reservar/confirmacion/' . $reservation['confirmation_code']);
    }
    
    /**
     * Página de confirmación
     */
    public function confirmation() {
        $code = $this->getParam('code');
        $reservation = $this->reservationModel->findByCode($code);
        
        if (!$reservation) {
            $this->redirect('reservar');
        }
        
        $this->render('client/booking/confirmation', [
            'reservation' => $reservation
        ], 'client');
    }
    
    /**
     * Consultar reservación
     */
    public function lookup() {
        $code = $this->getQuery('code');
        $reservation = null;
        $error = null;
        
        if ($code) {
            $reservation = $this->reservationModel->findByCode($code);
            if (!$reservation) {
                $error = 'No se encontró una reservación con ese código';
            }
        }
        
        $this->render('client/booking/lookup', [
            'code' => $code,
            'reservation' => $reservation,
            'error' => $error
        ], 'client');
    }
    
    /**
     * Modificar reservación
     */
    public function modify() {
        $code = $this->getParam('code');
        $reservation = $this->reservationModel->findByCode($code);
        
        if (!$reservation) {
            $this->redirect('reservar/consultar');
        }
        
        // No permitir modificar reservaciones pasadas o canceladas
        if (in_array($reservation['status'], ['cancelled', 'no_show', 'completed'])) {
            $this->setFlash('error', 'Esta reservación no puede ser modificada');
            $this->redirect('reservar/consultar?code=' . $code);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $this->getPost('date');
            $time = $this->getPost('time');
            $partySize = $this->getPost('party_size');
            
            $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
            
            // Verificar disponibilidad
            $tables = $this->tableModel->getAvailable(
                $reservation['restaurant_id'],
                $date,
                $time . ':00',
                $partySize,
                $restaurant['average_time_per_table']
            );
            
            if (count($tables) === 0) {
                $this->setFlash('error', 'No hay mesas disponibles para el nuevo horario');
                $this->redirect('reservar/modificar/' . $code);
                return;
            }
            
            // Obtener el ID de reservación
            $fullReservation = $this->reservationModel->find($reservation['id']);
            
            // Actualizar reservación
            $this->reservationModel->update($fullReservation['id'], [
                'reservation_date' => $date,
                'reservation_time' => $time . ':00',
                'party_size' => $partySize,
                'table_id' => $tables[0]['id'],
                'special_requests' => $this->getPost('special_requests')
            ]);
            
            $this->reservationModel->logHistory(
                $fullReservation['id'],
                'modified',
                json_encode([
                    'date' => $reservation['reservation_date'],
                    'time' => $reservation['reservation_time'],
                    'party_size' => $reservation['party_size']
                ]),
                json_encode([
                    'date' => $date,
                    'time' => $time,
                    'party_size' => $partySize
                ])
            );
            
            $this->setFlash('success', 'Reservación modificada exitosamente');
            $this->redirect('reservar/confirmacion/' . $code);
        }
        
        $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
        
        $this->render('client/booking/modify', [
            'reservation' => $reservation,
            'restaurant' => $restaurant
        ], 'client');
    }
    
    /**
     * Cancelar reservación
     */
    public function cancel() {
        $code = $this->getParam('code');
        $reservation = $this->reservationModel->findByCode($code);
        
        if (!$reservation) {
            $this->redirect('reservar/consultar');
        }
        
        // No permitir cancelar reservaciones pasadas o ya canceladas
        if (in_array($reservation['status'], ['cancelled', 'no_show', 'completed', 'seated'])) {
            $this->setFlash('error', 'Esta reservación no puede ser cancelada');
            $this->redirect('reservar/consultar?code=' . $code);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullReservation = $this->reservationModel->findByCode($code);
            
            $this->reservationModel->cancel($fullReservation['id'], 'Cancelado por el cliente');
            $this->customerModel->updateStats($fullReservation['customer_id'], 'cancellation');
            
            $this->setFlash('success', 'Tu reservación ha sido cancelada');
            $this->redirect('reservar/consultar?code=' . $code);
        }
        
        $this->render('client/booking/cancel', [
            'reservation' => $reservation
        ], 'client');
    }
    
    /**
     * API: Buscar disponibilidad
     */
    public function apiAvailability() {
        $restaurantId = $this->getQuery('restaurant_id');
        $date = $this->getQuery('date');
        $partySize = $this->getQuery('party_size', 2);
        
        if (!$restaurantId || !$date) {
            $this->json(['error' => 'Parámetros incompletos'], 400);
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        // Obtener horario del día
        $dayOfWeek = date('w', strtotime($date));
        $schedules = $this->restaurantModel->getSchedules($restaurantId);
        $daySchedule = null;
        
        foreach ($schedules as $schedule) {
            if ($schedule['day_of_week'] == $dayOfWeek) {
                $daySchedule = $schedule;
                break;
            }
        }
        
        if (!$daySchedule || $daySchedule['is_closed']) {
            $this->json([
                'available' => false,
                'message' => 'El restaurante está cerrado ese día',
                'slots' => []
            ]);
        }
        
        $openTime = $daySchedule['opening_time'] ?? $restaurant['opening_time'];
        $closeTime = $daySchedule['closing_time'] ?? $restaurant['closing_time'];
        $duration = $restaurant['average_time_per_table'];
        
        $slots = [];
        $currentTime = strtotime($date . ' ' . $openTime);
        $endTime = strtotime($date . ' ' . $closeTime) - ($duration * 60);
        
        while ($currentTime <= $endTime) {
            $timeStr = date('H:i:s', $currentTime);
            $tables = $this->tableModel->getAvailable($restaurantId, $date, $timeStr, $partySize, $duration);
            
            if (count($tables) > 0) {
                $slots[] = [
                    'time' => date('H:i', $currentTime),
                    'available_tables' => count($tables)
                ];
            }
            
            $currentTime += 30 * 60;
        }
        
        $this->json([
            'available' => count($slots) > 0,
            'slots' => $slots
        ]);
    }
}
