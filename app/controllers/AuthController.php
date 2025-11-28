<?php
/**
 * Controlador de Autenticación
 */

class AuthController extends Controller {
    private $userModel;
    
    public function __construct($params = []) {
        parent::__construct($params);
        $this->userModel = new UserModel();
    }
    
    /**
     * Mostrar formulario de login
     */
    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('admin/dashboard');
        }
        
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->getPost('username');
            $password = $this->getPost('password');
            
            $user = $this->userModel->authenticate($username, $password);
            
            if ($user) {
                if (!$user['is_active']) {
                    $error = 'Tu cuenta está desactivada. Contacta al administrador.';
                } else {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_avatar'] = $user['avatar'];
                    
                    $this->redirect('admin/dashboard');
                }
            } else {
                $error = 'Credenciales incorrectas';
            }
        }
        
        $this->render('auth/login', ['error' => $error], 'auth');
    }
    
    /**
     * Cerrar sesión
     */
    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
    
    /**
     * Mostrar formulario de registro
     */
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('admin/dashboard');
        }
        
        $error = null;
        $success = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $this->getPost('username'),
                'email' => $this->getPost('email'),
                'password' => $this->getPost('password'),
                'first_name' => $this->getPost('first_name'),
                'last_name' => $this->getPost('last_name'),
                'phone' => $this->getPost('phone'),
                'role' => 'customer'
            ];
            
            // Validaciones
            if ($this->userModel->usernameExists($data['username'])) {
                $error = 'El nombre de usuario ya existe';
            } elseif ($this->userModel->emailExists($data['email'])) {
                $error = 'El correo electrónico ya está registrado';
            } elseif (strlen($data['password']) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $this->userModel->createUser($data);
                $success = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
            }
        }
        
        $this->render('auth/register', ['error' => $error, 'success' => $success], 'auth');
    }
    
    /**
     * Recuperar contraseña
     */
    public function forgotPassword() {
        $error = null;
        $success = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->getPost('email');
            $user = $this->userModel->findBy('email', $email);
            
            if ($user) {
                // Aquí se implementaría el envío de correo con token de recuperación
                $success = 'Se ha enviado un correo con instrucciones para recuperar tu contraseña.';
            } else {
                $error = 'No existe una cuenta con ese correo electrónico.';
            }
        }
        
        $this->render('auth/forgot-password', ['error' => $error, 'success' => $success], 'auth');
    }
}
