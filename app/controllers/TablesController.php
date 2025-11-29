<?php
/**
 * Controlador de Mesas
 */

class TablesController extends Controller {
    private $tableModel;
    private $restaurantModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        
        $this->tableModel = new TableModel();
        $this->restaurantModel = new RestaurantModel();
    }
    
    /**
     * Listar mesas de un restaurante
     */
    public function index() {
        $restaurantId = $this->getParam('restaurant_id') ?? $this->getQuery('restaurant_id');
        
        if (!$restaurantId) {
            $restaurants = $this->restaurantModel->getActive();
            if (count($restaurants) === 1) {
                $restaurantId = $restaurants[0]['id'];
            } else {
                $this->render('admin/tables/select-restaurant', [
                    'restaurants' => $restaurants
                ], 'admin');
                return;
            }
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        $tables = $this->tableModel->getByRestaurant($restaurantId);
        $areas = $this->restaurantModel->getAreas($restaurantId);
        
        $this->render('admin/tables/index', [
            'restaurant' => $restaurant,
            'tables' => $tables,
            'areas' => $areas
        ], 'admin');
    }
    
    /**
     * Crear mesa
     */
    public function create() {
        $restaurantId = $this->getParam('restaurant_id');
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        if (!$restaurant) {
            $this->redirect('admin/tables');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'restaurant_id' => $restaurantId,
                'area_id' => $this->getPost('area_id') ?: null,
                'table_number' => $this->getPost('table_number'),
                'capacity' => $this->getPost('capacity'),
                'min_capacity' => $this->getPost('min_capacity', 1),
                'shape' => $this->getPost('shape', 'square'),
                'is_combinable' => $this->getPost('is_combinable') ? 1 : 0,
                'notes' => $this->getPost('notes'),
                'is_active' => 1
            ];
            
            $this->tableModel->create($data);
            
            $this->setFlash('success', 'Mesa creada exitosamente');
            $this->redirect('admin/tables?restaurant_id=' . $restaurantId);
        }
        
        $areas = $this->restaurantModel->getAreas($restaurantId);
        
        $this->render('admin/tables/create', [
            'restaurant' => $restaurant,
            'areas' => $areas
        ], 'admin');
    }
    
    /**
     * Editar mesa
     */
    public function edit() {
        $id = $this->getParam('id');
        $table = $this->tableModel->find($id);
        
        if (!$table) {
            $this->redirect('admin/tables');
        }
        
        $restaurant = $this->restaurantModel->find($table['restaurant_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'area_id' => $this->getPost('area_id') ?: null,
                'table_number' => $this->getPost('table_number'),
                'capacity' => $this->getPost('capacity'),
                'min_capacity' => $this->getPost('min_capacity', 1),
                'shape' => $this->getPost('shape', 'square'),
                'is_combinable' => $this->getPost('is_combinable') ? 1 : 0,
                'notes' => $this->getPost('notes'),
                'is_active' => $this->getPost('is_active') ? 1 : 0
            ];
            
            $this->tableModel->update($id, $data);
            
            $this->setFlash('success', 'Mesa actualizada exitosamente');
            $this->redirect('admin/tables?restaurant_id=' . $table['restaurant_id']);
        }
        
        $areas = $this->restaurantModel->getAreas($table['restaurant_id']);
        
        $this->render('admin/tables/edit', [
            'table' => $table,
            'restaurant' => $restaurant,
            'areas' => $areas
        ], 'admin');
    }
    
    /**
     * Eliminar mesa
     */
    public function delete() {
        $id = $this->getParam('id');
        $table = $this->tableModel->find($id);
        
        if ($table) {
            $this->tableModel->update($id, ['is_active' => 0]);
            $this->setFlash('success', 'Mesa desactivada exitosamente');
        }
        
        $restaurantId = $table['restaurant_id'] ?? null;
        $this->redirect('admin/tables' . ($restaurantId ? '?restaurant_id=' . $restaurantId : ''));
    }
    
    /**
     * Obtener disponibilidad de mesas (API)
     */
    public function availability() {
        $restaurantId = $this->getQuery('restaurant_id');
        $date = $this->getQuery('date');
        $time = $this->getQuery('time');
        $partySize = $this->getQuery('party_size', 2);
        
        if (!$restaurantId || !$date || !$time) {
            $this->json(['error' => 'Parámetros incompletos'], 400);
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        // Verificar si está abierto
        if (!$this->restaurantModel->isOpen($restaurantId, $date, $time)) {
            $this->json([
                'available' => false,
                'message' => 'El restaurante está cerrado en ese horario'
            ]);
        }
        
        $tables = $this->tableModel->getAvailable($restaurantId, $date, $time, $partySize);
        
        $this->json([
            'available' => count($tables) > 0,
            'tables' => $tables
        ]);
    }
    
    /**
     * Ver mapa de mesas
     */
    public function map() {
        $restaurantId = $this->getParam('restaurant_id') ?? $this->getQuery('restaurant_id');
        $date = $this->getQuery('date', date('Y-m-d'));
        
        if (!$restaurantId) {
            $this->redirect('admin/tables');
        }
        
        $restaurant = $this->restaurantModel->find($restaurantId);
        $occupancy = $this->tableModel->getOccupancy($restaurantId, $date);
        $areas = $this->restaurantModel->getAreas($restaurantId);
        
        $this->render('admin/tables/map', [
            'restaurant' => $restaurant,
            'occupancy' => $occupancy,
            'areas' => $areas,
            'date' => $date
        ], 'admin');
    }
    
    /**
     * Bloquear mesa
     */
    public function block() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $tableId = $this->getPost('table_id');
        $date = $this->getPost('date');
        $startTime = $this->getPost('start_time');
        $endTime = $this->getPost('end_time');
        $reason = $this->getPost('reason');
        
        $this->tableModel->block($tableId, $date, $startTime, $endTime, $reason, $_SESSION['user_id']);
        
        $this->json(['success' => true, 'message' => 'Mesa bloqueada exitosamente']);
    }
    
    /**
     * Desbloquear mesa
     */
    public function unblock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $blockId = $this->getPost('block_id');
        $this->tableModel->unblock($blockId);
        
        $this->json(['success' => true, 'message' => 'Bloqueo eliminado']);
    }
    
    /**
     * Gestionar áreas
     */
    public function areas() {
        $restaurantId = $this->getParam('restaurant_id');
        $restaurant = $this->restaurantModel->find($restaurantId);
        
        if (!$restaurant) {
            $this->redirect('admin/restaurants');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $this->getPost('action');
            
            if ($action === 'create') {
                $this->db->insert('restaurant_areas', [
                    'restaurant_id' => $restaurantId,
                    'name' => $this->getPost('name'),
                    'description' => $this->getPost('description'),
                    'is_outdoor' => $this->getPost('is_outdoor') ? 1 : 0,
                    'is_vip' => $this->getPost('is_vip') ? 1 : 0,
                    'is_private' => $this->getPost('is_private') ? 1 : 0,
                    'surcharge' => $this->getPost('surcharge', 0),
                    'display_order' => $this->getPost('display_order', 0)
                ]);
                $this->setFlash('success', 'Área creada exitosamente');
            } elseif ($action === 'update') {
                $areaId = $this->getPost('area_id');
                $this->db->update('restaurant_areas', [
                    'name' => $this->getPost('name'),
                    'description' => $this->getPost('description'),
                    'is_outdoor' => $this->getPost('is_outdoor') ? 1 : 0,
                    'is_vip' => $this->getPost('is_vip') ? 1 : 0,
                    'is_private' => $this->getPost('is_private') ? 1 : 0,
                    'surcharge' => $this->getPost('surcharge', 0),
                    'display_order' => $this->getPost('display_order', 0)
                ], 'id = :id', ['id' => $areaId]);
                $this->setFlash('success', 'Área actualizada exitosamente');
            } elseif ($action === 'delete') {
                $areaId = $this->getPost('area_id');
                $this->db->update('restaurant_areas', ['is_active' => 0], 'id = :id', ['id' => $areaId]);
                $this->setFlash('success', 'Área desactivada');
            }
            
            $this->redirect('admin/restaurants/' . $restaurantId . '/areas');
        }
        
        $areas = $this->restaurantModel->getAreas($restaurantId);
        
        $this->render('admin/tables/areas', [
            'restaurant' => $restaurant,
            'areas' => $areas
        ], 'admin');
    }
}
