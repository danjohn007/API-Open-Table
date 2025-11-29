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
            
            // Validate captcha
            $captcha = $this->getPost('captcha');
            $captchaAnswer = $_SESSION['captcha_answer'] ?? null;
            
            // Validate terms acceptance
            $acceptTerms = $this->getPost('accept_terms');
            
            // Validate phone (10 digits)
            $phone = preg_replace('/[^0-9]/', '', $data['phone']);
            
            // Validaciones
            if ($captchaAnswer === null || intval($captcha) !== intval($captchaAnswer)) {
                $error = 'La verificación es incorrecta. Por favor intenta de nuevo.';
            } elseif (!$acceptTerms) {
                $error = 'Debes aceptar los Términos y Condiciones para continuar';
            } elseif (strlen($phone) !== 10) {
                $error = 'El teléfono debe tener exactamente 10 dígitos';
            } elseif ($this->userModel->usernameExists($data['username'])) {
                $error = 'El nombre de usuario ya existe';
            } elseif ($this->userModel->emailExists($data['email'])) {
                $error = 'El correo electrónico ya está registrado';
            } elseif (strlen($data['password']) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $data['phone'] = $phone;
                $this->userModel->createUser($data);
                $success = 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.';
            }
            
            // Clear captcha answer
            unset($_SESSION['captcha_answer']);
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
    
    /**
     * Perfil del usuario
     */
    public function profile() {
        $this->requireAuth();
        
        $user = $this->userModel->find($_SESSION['user_id']);
        
        if (!$user) {
            $this->redirect('login');
        }
        
        $error = null;
        $success = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'first_name' => $this->getPost('first_name'),
                'last_name' => $this->getPost('last_name'),
                'email' => $this->getPost('email'),
                'phone' => $this->getPost('phone')
            ];
            
            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'El formato del correo electrónico no es válido';
            } elseif ($data['email'] !== $user['email'] && $this->userModel->emailExists($data['email'])) {
                // Check if email is being changed and if it already exists
                $error = 'El correo electrónico ya está registrado';
            } else {
                // Handle profile image upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                    $avatarPath = $this->uploadProfileImage($_FILES['avatar']);
                    if ($avatarPath) {
                        $data['avatar'] = $avatarPath;
                    }
                }
                
                // Update password if provided
                $newPassword = $this->getPost('new_password');
                if (!empty($newPassword)) {
                    if (strlen($newPassword) < 6) {
                        $error = 'La contraseña debe tener al menos 6 caracteres';
                    } else {
                        $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                    }
                }
                
                if (!$error) {
                    $result = $this->userModel->update($_SESSION['user_id'], $data);
                    if ($result) {
                        $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                        $_SESSION['user_email'] = $data['email'];
                        if (isset($data['avatar'])) {
                            $_SESSION['user_avatar'] = $data['avatar'];
                        }
                        $success = 'Perfil actualizado exitosamente';
                        $user = $this->userModel->find($_SESSION['user_id']);
                    } else {
                        $error = 'Error al actualizar el perfil';
                    }
                }
            }
        }
        
        $this->render('admin/profile', [
            'user' => $user,
            'error' => $error,
            'success' => $success
        ], 'admin');
    }
    
    /**
     * Upload profile image
     */
    private function uploadProfileImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }
        
        $targetDir = UPLOADS_PATH . '/avatars';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $targetPath = $targetDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return 'uploads/avatars/' . $filename;
        }
        
        return null;
    }
}
