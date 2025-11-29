<?php
/**
 * Controlador de Clientes
 */

class CustomersController extends Controller {
    private $customerModel;
    private $reservationModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        
        $this->customerModel = new CustomerModel();
        $this->reservationModel = new ReservationModel();
    }
    
    /**
     * Listar clientes
     */
    public function index() {
        $search = $this->getQuery('search');
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = 20;
        
        if ($search) {
            $customers = $this->customerModel->search($search);
            $pagination = null;
        } else {
            $result = $this->customerModel->paginate($page, $perPage, null, [], 'first_name, last_name ASC');
            $customers = $result['data'];
            $pagination = $result;
        }
        
        $this->render('admin/customers/index', [
            'customers' => $customers,
            'pagination' => $pagination,
            'search' => $search
        ], 'admin');
    }
    
    /**
     * Mostrar cliente
     */
    public function show() {
        $id = $this->getParam('id');
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $this->redirect('admin/customers');
        }
        
        $history = $this->customerModel->getReservationHistory($id);
        
        $this->render('admin/customers/show', [
            'customer' => $customer,
            'history' => $history
        ], 'admin');
    }
    
    /**
     * Crear cliente
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->getPost('first_name'),
                'last_name' => $this->getPost('last_name'),
                'email' => $this->getPost('email'),
                'phone' => $this->getPost('phone'),
                'notes' => $this->getPost('notes'),
                'vip_status' => $this->getPost('vip_status') ? 1 : 0
            ];
            
            $id = $this->customerModel->create($data);
            
            $this->setFlash('success', 'Cliente creado exitosamente');
            $this->redirect('admin/customers/' . $id);
        }
        
        $this->render('admin/customers/create', [], 'admin');
    }
    
    /**
     * Editar cliente
     */
    public function edit() {
        $id = $this->getParam('id');
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $this->redirect('admin/customers');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->getPost('first_name'),
                'last_name' => $this->getPost('last_name'),
                'email' => $this->getPost('email'),
                'phone' => $this->getPost('phone'),
                'notes' => $this->getPost('notes'),
                'vip_status' => $this->getPost('vip_status') ? 1 : 0
            ];
            
            $this->customerModel->update($id, $data);
            
            $this->setFlash('success', 'Cliente actualizado exitosamente');
            $this->redirect('admin/customers/' . $id);
        }
        
        $this->render('admin/customers/edit', [
            'customer' => $customer
        ], 'admin');
    }
    
    /**
     * Buscar clientes (API)
     */
    public function search() {
        $query = $this->getQuery('q');
        
        if (!$query || strlen($query) < 2) {
            $this->json([]);
        }
        
        $customers = $this->customerModel->search($query);
        
        $this->json($customers);
    }
    
    /**
     * Cambiar estado VIP
     */
    public function toggleVip() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $id = $this->getPost('customer_id');
        $customer = $this->customerModel->find($id);
        
        if (!$customer) {
            $this->json(['error' => 'Cliente no encontrado'], 404);
        }
        
        $newStatus = !$customer['vip_status'];
        $this->customerModel->setVipStatus($id, $newStatus);
        
        $this->json([
            'success' => true,
            'vip_status' => $newStatus
        ]);
    }
}
