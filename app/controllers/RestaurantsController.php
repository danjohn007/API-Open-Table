<?php
/**
 * Controlador de Restaurantes
 */

class RestaurantsController extends Controller {
    private $restaurantModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        $this->restaurantModel = new RestaurantModel();
    }
    
    /**
     * Listar restaurantes
     */
    public function index() {
        $restaurants = $this->restaurantModel->all('name ASC');
        
        $this->render('admin/restaurants/index', [
            'restaurants' => $restaurants
        ], 'admin');
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->store();
        }
        
        $this->render('admin/restaurants/create', [], 'admin');
    }
    
    /**
     * Guardar nuevo restaurante
     */
    private function store() {
        $data = [
            'name' => $this->getPost('name'),
            'slug' => $this->restaurantModel->generateSlug($this->getPost('name')),
            'description' => $this->getPost('description'),
            'address' => $this->getPost('address'),
            'city' => $this->getPost('city'),
            'state' => $this->getPost('state'),
            'postal_code' => $this->getPost('postal_code'),
            'country' => $this->getPost('country', 'México'),
            'phone' => $this->getPost('phone'),
            'email' => $this->getPost('email'),
            'website' => $this->getPost('website'),
            'opening_time' => $this->getPost('opening_time'),
            'closing_time' => $this->getPost('closing_time'),
            'average_time_per_table' => $this->getPost('average_time_per_table', 90),
            'max_party_size' => $this->getPost('max_party_size', 20),
            'min_party_size' => $this->getPost('min_party_size', 1),
            'advance_booking_days' => $this->getPost('advance_booking_days', 30),
            'cancellation_hours' => $this->getPost('cancellation_hours', 24),
            'is_active' => $this->getPost('is_active') ? 1 : 0
        ];
        
        // Manejar logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $data['logo'] = $this->uploadFile($_FILES['logo'], 'logos');
        }
        
        $id = $this->restaurantModel->create($data);
        
        // Guardar horarios
        $this->saveSchedules($id);
        
        $this->setFlash('success', 'Restaurante creado exitosamente');
        $this->redirect('admin/restaurants');
    }
    
    /**
     * Mostrar restaurante
     */
    public function show() {
        $id = $this->getParam('id');
        $restaurant = $this->restaurantModel->find($id);
        
        if (!$restaurant) {
            $this->redirect('admin/restaurants');
        }
        
        $areas = $this->restaurantModel->getAreas($id);
        $tables = $this->restaurantModel->getTables($id);
        $schedules = $this->restaurantModel->getSchedules($id);
        $stats = $this->restaurantModel->getStats($id);
        
        $this->render('admin/restaurants/show', [
            'restaurant' => $restaurant,
            'areas' => $areas,
            'tables' => $tables,
            'schedules' => $schedules,
            'stats' => $stats
        ], 'admin');
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit() {
        $this->requireAdmin();
        
        $id = $this->getParam('id');
        $restaurant = $this->restaurantModel->find($id);
        
        if (!$restaurant) {
            $this->redirect('admin/restaurants');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->update($id);
        }
        
        $schedules = $this->restaurantModel->getSchedules($id);
        
        $this->render('admin/restaurants/edit', [
            'restaurant' => $restaurant,
            'schedules' => $schedules
        ], 'admin');
    }
    
    /**
     * Actualizar restaurante
     */
    private function update($id) {
        $data = [
            'name' => $this->getPost('name'),
            'description' => $this->getPost('description'),
            'address' => $this->getPost('address'),
            'city' => $this->getPost('city'),
            'state' => $this->getPost('state'),
            'postal_code' => $this->getPost('postal_code'),
            'country' => $this->getPost('country', 'México'),
            'phone' => $this->getPost('phone'),
            'email' => $this->getPost('email'),
            'website' => $this->getPost('website'),
            'opening_time' => $this->getPost('opening_time'),
            'closing_time' => $this->getPost('closing_time'),
            'average_time_per_table' => $this->getPost('average_time_per_table', 90),
            'max_party_size' => $this->getPost('max_party_size', 20),
            'min_party_size' => $this->getPost('min_party_size', 1),
            'advance_booking_days' => $this->getPost('advance_booking_days', 30),
            'cancellation_hours' => $this->getPost('cancellation_hours', 24),
            'is_active' => $this->getPost('is_active') ? 1 : 0
        ];
        
        // Actualizar slug si cambió el nombre
        $restaurant = $this->restaurantModel->find($id);
        if ($restaurant['name'] !== $data['name']) {
            $data['slug'] = $this->restaurantModel->generateSlug($data['name'], $id);
        }
        
        // Manejar logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $data['logo'] = $this->uploadFile($_FILES['logo'], 'logos');
        }
        
        $this->restaurantModel->update($id, $data);
        
        // Guardar horarios
        $this->saveSchedules($id);
        
        $this->setFlash('success', 'Restaurante actualizado exitosamente');
        $this->redirect('admin/restaurants/' . $id);
    }
    
    /**
     * Eliminar restaurante
     */
    public function delete() {
        $this->requireAdmin();
        
        $id = $this->getParam('id');
        $this->restaurantModel->update($id, ['is_active' => 0]);
        
        $this->setFlash('success', 'Restaurante desactivado exitosamente');
        $this->redirect('admin/restaurants');
    }
    
    /**
     * Guardar horarios
     */
    private function saveSchedules($restaurantId) {
        $schedules = [];
        $days = $this->getPost('schedules');
        
        if ($days && is_array($days)) {
            foreach ($days as $day => $schedule) {
                $schedules[] = [
                    'day_of_week' => $day,
                    'opening_time' => $schedule['opening'] ?? '00:00',
                    'closing_time' => $schedule['closing'] ?? '00:00',
                    'is_closed' => isset($schedule['closed']) ? 1 : 0
                ];
            }
        }
        
        $this->restaurantModel->saveSchedules($restaurantId, $schedules);
    }
    
    /**
     * Subir archivo
     */
    private function uploadFile($file, $folder) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }
        
        $targetDir = UPLOADS_PATH . '/' . $folder;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $targetDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/' . $folder . '/' . $filename;
        }
        
        return null;
    }
}
