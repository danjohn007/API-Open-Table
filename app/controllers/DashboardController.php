<?php
/**
 * Controlador del Dashboard Administrativo
 */

class DashboardController extends Controller {
    private $restaurantModel;
    private $reservationModel;
    private $customerModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        
        $this->restaurantModel = new RestaurantModel();
        $this->reservationModel = new ReservationModel();
        $this->customerModel = new CustomerModel();
    }
    
    /**
     * Mostrar dashboard principal
     */
    public function index() {
        $restaurants = $this->restaurantModel->getActive();
        $pendingReservations = $this->reservationModel->getPending();
        
        // Estadísticas generales
        $stats = [
            'total_restaurants' => count($restaurants),
            'pending_reservations' => count($pendingReservations),
            'today_reservations' => 0,
            'total_customers' => $this->customerModel->count()
        ];
        
        // Reservaciones de hoy por restaurante
        $todayReservations = [];
        foreach ($restaurants as $restaurant) {
            $reservations = $this->reservationModel->getToday($restaurant['id']);
            $stats['today_reservations'] += count($reservations);
            $todayReservations[$restaurant['id']] = $reservations;
        }
        
        // Datos para gráficas
        $chartData = $this->getChartData();
        
        $this->render('admin/dashboard', [
            'restaurants' => $restaurants,
            'pendingReservations' => $pendingReservations,
            'todayReservations' => $todayReservations,
            'stats' => $stats,
            'chartData' => $chartData
        ], 'admin');
    }
    
    /**
     * Obtener datos para gráficas
     */
    private function getChartData() {
        $restaurants = $this->restaurantModel->getActive();
        
        // Últimos 7 días
        $days = [];
        $reservationsByDay = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $days[] = date('d/m', strtotime($date));
            
            $count = 0;
            foreach ($restaurants as $restaurant) {
                $reservations = $this->reservationModel->getByRestaurantAndDate($restaurant['id'], $date);
                $count += count($reservations);
            }
            $reservationsByDay[] = $count;
        }
        
        // Reservaciones por estado (este mes)
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
        
        $statusCounts = [
            'completed' => 0,
            'cancelled' => 0,
            'no_show' => 0,
            'confirmed' => 0,
            'pending' => 0
        ];
        
        foreach ($restaurants as $restaurant) {
            $stats = $this->reservationModel->getStats($restaurant['id'], $firstDay, $lastDay);
            $statusCounts['completed'] += (int) $stats['completed'];
            $statusCounts['cancelled'] += (int) $stats['cancelled'];
            $statusCounts['no_show'] += (int) $stats['no_shows'];
        }
        
        return [
            'labels' => $days,
            'reservations' => $reservationsByDay,
            'statusCounts' => $statusCounts
        ];
    }
    
    /**
     * Cambiar restaurante activo en sesión
     */
    public function selectRestaurant() {
        $restaurantId = $this->getPost('restaurant_id');
        
        if ($restaurantId) {
            $_SESSION['selected_restaurant_id'] = $restaurantId;
        } else {
            unset($_SESSION['selected_restaurant_id']);
        }
        
        $this->json(['success' => true]);
    }
}
