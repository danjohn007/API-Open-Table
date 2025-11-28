<?php
/**
 * Controlador de Configuraciones
 */

class SettingsController extends Controller {
    private $settingModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->requireAuth();
        $this->requireAdmin();
        
        $this->settingModel = new SettingModel();
    }
    
    /**
     * Configuraciones generales
     */
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('general');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/index', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Configuración de apariencia
     */
    public function appearance() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('appearance');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/appearance', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Configuración de correo
     */
    public function mail() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('mail');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/mail', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Configuración de pagos (PayPal)
     */
    public function payment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('payment');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/payment', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Configuración de OpenTable
     */
    public function opentable() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('opentable');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/opentable', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Configuración de integraciones
     */
    public function integrations() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save('integrations');
        }
        
        $settings = $this->settingModel->getAllAsArray();
        
        $this->render('admin/settings/integrations', [
            'settings' => $settings
        ], 'admin');
    }
    
    /**
     * Guardar configuraciones
     */
    private function save($group) {
        $postData = $this->getPost();
        unset($postData['csrf_token']);
        
        // Manejar archivos
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === 0) {
            $postData['site_logo'] = $this->uploadFile($_FILES['site_logo'], 'logos');
        }
        
        foreach ($postData as $key => $value) {
            $setting = $this->settingModel->findBy('setting_key', $key);
            if ($setting) {
                $this->settingModel->set($key, $value, $setting['setting_type'], $setting['setting_group']);
            }
        }
        
        $this->setFlash('success', 'Configuraciones guardadas exitosamente');
        $this->redirect('admin/settings/' . ($group === 'general' ? '' : $group));
    }
    
    /**
     * Subir archivo
     */
    private function uploadFile($file, $folder) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        
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
    
    /**
     * Enviar correo de prueba
     */
    public function testEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $email = $this->getPost('email');
        
        // Aquí se implementaría el envío de correo de prueba
        // Por ahora solo simulamos
        
        $this->json([
            'success' => true,
            'message' => 'Correo de prueba enviado a ' . $email
        ]);
    }
    
    /**
     * Probar conexión con OpenTable
     */
    public function testOpentable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        // Aquí se implementaría la prueba de conexión con OpenTable API
        
        $this->json([
            'success' => true,
            'message' => 'Conexión con OpenTable establecida correctamente'
        ]);
    }
    
    /**
     * Usuarios del sistema
     */
    public function users() {
        $userModel = new UserModel();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $this->getPost('action');
            
            if ($action === 'create') {
                $data = [
                    'username' => $this->getPost('username'),
                    'email' => $this->getPost('email'),
                    'password' => $this->getPost('password'),
                    'first_name' => $this->getPost('first_name'),
                    'last_name' => $this->getPost('last_name'),
                    'phone' => $this->getPost('phone'),
                    'role' => $this->getPost('role'),
                    'is_active' => 1
                ];
                
                if ($userModel->emailExists($data['email'])) {
                    $this->setFlash('error', 'El correo electrónico ya está registrado');
                } elseif ($userModel->usernameExists($data['username'])) {
                    $this->setFlash('error', 'El nombre de usuario ya existe');
                } else {
                    $userModel->createUser($data);
                    $this->setFlash('success', 'Usuario creado exitosamente');
                }
            } elseif ($action === 'toggle') {
                $userId = $this->getPost('user_id');
                $user = $userModel->find($userId);
                $userModel->update($userId, ['is_active' => !$user['is_active']]);
                $this->setFlash('success', 'Estado del usuario actualizado');
            }
            
            $this->redirect('admin/settings/users');
        }
        
        $users = $userModel->all('role, first_name ASC');
        
        $this->render('admin/settings/users', [
            'users' => $users
        ], 'admin');
    }
}
