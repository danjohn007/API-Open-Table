<?php
/**
 * Controlador de Reservaciones
 */

class ReservationsController extends Controller {
    private $reservationModel;
    private $restaurantModel;
    private $tableModel;
    private $customerModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        
        $this->reservationModel = new ReservationModel();
        $this->restaurantModel = new RestaurantModel();
        $this->tableModel = new TableModel();
        $this->customerModel = new CustomerModel();
    }
    
    /**
     * Listar reservaciones
     */
    public function index() {
        $restaurantId = $this->getQuery('restaurant_id');
        $date = $this->getQuery('date', date('Y-m-d'));
        $status = $this->getQuery('status');
        
        $restaurants = $this->restaurantModel->getActive();
        
        if (!$restaurantId && count($restaurants) > 0) {
            $restaurantId = $restaurants[0]['id'];
        }
        
        $reservations = [];
        $restaurant = null;
        
        if ($restaurantId) {
            $restaurant = $this->restaurantModel->find($restaurantId);
            $reservations = $this->reservationModel->getByRestaurantAndDate($restaurantId, $date, $status);
        }
        
        $this->render('admin/reservations/index', [
            'reservations' => $reservations,
            'restaurants' => $restaurants,
            'selectedRestaurant' => $restaurant,
            'selectedDate' => $date,
            'selectedStatus' => $status
        ], 'admin');
    }
    
    /**
     * Crear reservación
     */
    public function create() {
        $restaurantId = $this->getQuery('restaurant_id');
        $restaurants = $this->restaurantModel->getActive();
        
        if (!$restaurantId && count($restaurants) > 0) {
            $restaurantId = $restaurants[0]['id'];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->store();
        }
        
        $restaurant = $restaurantId ? $this->restaurantModel->find($restaurantId) : null;
        $tables = $restaurantId ? $this->tableModel->getByRestaurant($restaurantId) : [];
        $areas = $restaurantId ? $this->restaurantModel->getAreas($restaurantId) : [];
        
        $this->render('admin/reservations/create', [
            'restaurants' => $restaurants,
            'restaurant' => $restaurant,
            'tables' => $tables,
            'areas' => $areas
        ], 'admin');
    }
    
    /**
     * Guardar nueva reservación
     */
    private function store() {
        $restaurantId = $this->getPost('restaurant_id');
        $date = $this->getPost('reservation_date');
        $time = $this->getPost('reservation_time');
        $partySize = $this->getPost('party_size');
        $tableId = $this->getPost('table_id') ?: null;
        
        // Crear o buscar cliente
        $customerId = $this->customerModel->findOrCreate([
            'first_name' => $this->getPost('customer_first_name'),
            'last_name' => $this->getPost('customer_last_name'),
            'email' => $this->getPost('customer_email'),
            'phone' => $this->getPost('customer_phone')
        ]);
        
        // Verificar disponibilidad si hay mesa asignada
        if ($tableId) {
            $restaurant = $this->restaurantModel->find($restaurantId);
            $duration = $restaurant['average_time_per_table'] ?? 90;
            
            if (!$this->tableModel->isAvailable($tableId, $date, $time, $duration)) {
                $this->setFlash('error', 'La mesa seleccionada no está disponible en ese horario');
                $this->redirect('admin/reservations/create?restaurant_id=' . $restaurantId);
                return;
            }
        }
        
        $data = [
            'restaurant_id' => $restaurantId,
            'customer_id' => $customerId,
            'table_id' => $tableId,
            'reservation_date' => $date,
            'reservation_time' => $time,
            'party_size' => $partySize,
            'duration_minutes' => $this->getPost('duration_minutes', 90),
            'status' => $this->getPost('status', 'confirmed'),
            'source' => 'internal',
            'special_requests' => $this->getPost('special_requests'),
            'occasion' => $this->getPost('occasion'),
            'area_preference' => $this->getPost('area_preference'),
            'internal_notes' => $this->getPost('internal_notes'),
            'created_by' => $_SESSION['user_id']
        ];
        
        $id = $this->reservationModel->createReservation($data);
        
        $this->setFlash('success', 'Reservación creada exitosamente');
        $this->redirect('admin/reservations/' . $id);
    }
    
    /**
     * Mostrar reservación
     */
    public function show() {
        $id = $this->getParam('id');
        $reservation = $this->reservationModel->find($id);
        
        if (!$reservation) {
            $this->redirect('admin/reservations');
        }
        
        $reservation = $this->reservationModel->findByCode($reservation['confirmation_code']);
        $history = $this->reservationModel->getHistory($id);
        $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
        $tables = $this->tableModel->getByRestaurant($reservation['restaurant_id']);
        
        $this->render('admin/reservations/show', [
            'reservation' => $reservation,
            'history' => $history,
            'restaurant' => $restaurant,
            'tables' => $tables
        ], 'admin');
    }
    
    /**
     * Editar reservación
     */
    public function edit() {
        $id = $this->getParam('id');
        $reservation = $this->reservationModel->find($id);
        
        if (!$reservation) {
            $this->redirect('admin/reservations');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->update($id);
        }
        
        $fullReservation = $this->reservationModel->findByCode($reservation['confirmation_code']);
        $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
        $tables = $this->tableModel->getByRestaurant($reservation['restaurant_id']);
        $areas = $this->restaurantModel->getAreas($reservation['restaurant_id']);
        
        $this->render('admin/reservations/edit', [
            'reservation' => $fullReservation,
            'restaurant' => $restaurant,
            'tables' => $tables,
            'areas' => $areas
        ], 'admin');
    }
    
    /**
     * Actualizar reservación
     */
    private function update($id) {
        $reservation = $this->reservationModel->find($id);
        
        $tableId = $this->getPost('table_id') ?: null;
        $date = $this->getPost('reservation_date');
        $time = $this->getPost('reservation_time');
        
        // Verificar disponibilidad si cambió la mesa, fecha u hora
        if ($tableId && (
            $tableId != $reservation['table_id'] ||
            $date != $reservation['reservation_date'] ||
            $time != $reservation['reservation_time']
        )) {
            $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
            $duration = $this->getPost('duration_minutes', $restaurant['average_time_per_table']);
            
            if (!$this->tableModel->isAvailable($tableId, $date, $time, $duration, $id)) {
                $this->setFlash('error', 'La mesa no está disponible en el nuevo horario');
                $this->redirect('admin/reservations/' . $id . '/edit');
                return;
            }
        }
        
        $data = [
            'table_id' => $tableId,
            'reservation_date' => $date,
            'reservation_time' => $time,
            'party_size' => $this->getPost('party_size'),
            'duration_minutes' => $this->getPost('duration_minutes', 90),
            'special_requests' => $this->getPost('special_requests'),
            'occasion' => $this->getPost('occasion'),
            'area_preference' => $this->getPost('area_preference'),
            'internal_notes' => $this->getPost('internal_notes')
        ];
        
        // Verificar si hubo cambios
        $oldData = json_encode([
            'date' => $reservation['reservation_date'],
            'time' => $reservation['reservation_time'],
            'party_size' => $reservation['party_size'],
            'table_id' => $reservation['table_id']
        ]);
        
        $this->reservationModel->update($id, $data);
        
        $newData = json_encode([
            'date' => $data['reservation_date'],
            'time' => $data['reservation_time'],
            'party_size' => $data['party_size'],
            'table_id' => $data['table_id']
        ]);
        
        if ($oldData !== $newData) {
            $this->reservationModel->logHistory($id, 'modified', $oldData, $newData, $_SESSION['user_id']);
        }
        
        $this->setFlash('success', 'Reservación actualizada exitosamente');
        $this->redirect('admin/reservations/' . $id);
    }
    
    /**
     * Cambiar estado de reservación
     */
    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $id = $this->getPost('reservation_id');
        $status = $this->getPost('status');
        $reason = $this->getPost('reason');
        
        $validStatuses = ['pending', 'confirmed', 'waiting', 'seated', 'completed', 'cancelled', 'no_show'];
        
        if (!in_array($status, $validStatuses)) {
            $this->json(['error' => 'Estado inválido'], 400);
        }
        
        $this->reservationModel->changeStatus($id, $status, $_SESSION['user_id'], $reason);
        
        // Actualizar estadísticas del cliente si es necesario
        $reservation = $this->reservationModel->find($id);
        if ($status === 'completed') {
            $this->customerModel->updateStats($reservation['customer_id'], 'visit');
        } elseif ($status === 'no_show') {
            $this->customerModel->updateStats($reservation['customer_id'], 'no_show');
        } elseif ($status === 'cancelled') {
            $this->customerModel->updateStats($reservation['customer_id'], 'cancellation');
        }
        
        $this->json(['success' => true, 'message' => 'Estado actualizado']);
    }
    
    /**
     * Check-in de cliente
     */
    public function checkIn() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $id = $this->getPost('reservation_id');
        $this->reservationModel->checkIn($id, $_SESSION['user_id']);
        
        $this->json(['success' => true, 'message' => 'Check-in realizado']);
    }
    
    /**
     * Asignar mesa
     */
    public function assignTable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $id = $this->getPost('reservation_id');
        $tableId = $this->getPost('table_id');
        
        $reservation = $this->reservationModel->find($id);
        $restaurant = $this->restaurantModel->find($reservation['restaurant_id']);
        
        // Verificar disponibilidad
        if (!$this->tableModel->isAvailable(
            $tableId, 
            $reservation['reservation_date'], 
            $reservation['reservation_time'], 
            $reservation['duration_minutes'],
            $id
        )) {
            $this->json(['error' => 'La mesa no está disponible'], 400);
        }
        
        $this->reservationModel->assignTable($id, $tableId, $_SESSION['user_id']);
        
        $this->json(['success' => true, 'message' => 'Mesa asignada']);
    }
    
    /**
     * Cancelar reservación
     */
    public function cancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $id = $this->getPost('reservation_id');
        $reason = $this->getPost('reason');
        
        $this->reservationModel->cancel($id, $reason, $_SESSION['user_id']);
        
        $reservation = $this->reservationModel->find($id);
        $this->customerModel->updateStats($reservation['customer_id'], 'cancellation');
        
        $this->json(['success' => true, 'message' => 'Reservación cancelada']);
    }
    
    /**
     * Calendario de reservaciones
     */
    public function calendar() {
        $restaurantId = $this->getQuery('restaurant_id');
        $restaurants = $this->restaurantModel->getActive();
        
        if (!$restaurantId && count($restaurants) > 0) {
            $restaurantId = $restaurants[0]['id'];
        }
        
        $restaurant = $restaurantId ? $this->restaurantModel->find($restaurantId) : null;
        
        $this->render('admin/reservations/calendar', [
            'restaurants' => $restaurants,
            'restaurant' => $restaurant
        ], 'admin');
    }
    
    /**
     * Obtener eventos para el calendario (API)
     */
    public function calendarEvents() {
        $restaurantId = $this->getQuery('restaurant_id');
        $start = $this->getQuery('start');
        $end = $this->getQuery('end');
        
        if (!$restaurantId) {
            $this->json([]);
        }
        
        $reservations = $this->reservationModel->getByDateRange($restaurantId, $start, $end);
        
        $events = [];
        foreach ($reservations as $reservation) {
            $statusColors = [
                'pending' => '#fbbf24',
                'confirmed' => '#22c55e',
                'waiting' => '#3b82f6',
                'seated' => '#8b5cf6',
                'completed' => '#6b7280',
                'cancelled' => '#ef4444',
                'no_show' => '#f97316'
            ];
            
            $events[] = [
                'id' => $reservation['id'],
                'title' => $reservation['customer_first_name'] . ' ' . $reservation['customer_last_name'] . ' (' . $reservation['party_size'] . ')',
                'start' => $reservation['reservation_date'] . 'T' . $reservation['reservation_time'],
                'end' => date('Y-m-d\TH:i:s', strtotime($reservation['reservation_date'] . ' ' . $reservation['reservation_time']) + ($reservation['duration_minutes'] * 60)),
                'backgroundColor' => $statusColors[$reservation['status']] ?? '#6b7280',
                'borderColor' => $statusColors[$reservation['status']] ?? '#6b7280',
                'extendedProps' => [
                    'status' => $reservation['status'],
                    'table' => $reservation['table_number'],
                    'confirmationCode' => $reservation['confirmation_code']
                ]
            ];
        }
        
        $this->json($events);
    }
    
    /**
     * Buscar disponibilidad (API)
     */
    public function searchAvailability() {
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
        
        // Generar slots de tiempo
        $slots = [];
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
            
            $currentTime += 30 * 60; // Incrementar 30 minutos
        }
        
        $this->json([
            'available' => count($slots) > 0,
            'slots' => $slots
        ]);
    }
}
