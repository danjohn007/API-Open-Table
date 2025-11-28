<?php
/**
 * Controlador Base
 */

abstract class Controller {
    protected $params = [];
    protected $db;
    
    public function __construct($params = []) {
        $this->params = $params;
        $this->db = Database::getInstance();
    }
    
    /**
     * Renderizar una vista
     */
    protected function render($view, $data = [], $layout = 'main') {
        extract($data);
        
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new Exception("Vista no encontrada: $view");
        }
        
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        
        if ($layout) {
            $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';
            if (file_exists($layoutFile)) {
                include $layoutFile;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }
    
    /**
     * Renderizar JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redireccionar
     */
    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Obtener parámetro de la URL
     */
    protected function getParam($key, $default = null) {
        return $this->params[$key] ?? $default;
    }
    
    /**
     * Verificar si el usuario está autenticado
     */
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }
    
    /**
     * Verificar si el usuario es administrador
     */
    protected function requireAdmin() {
        $this->requireAuth();
        if ($_SESSION['user_role'] !== 'admin') {
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Obtener datos POST
     */
    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Obtener datos GET
     */
    protected function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Generar token CSRF
     */
    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validar token CSRF
     */
    protected function validateCsrf() {
        $token = $this->getPost('csrf_token') ?? $this->getHeader('X-CSRF-Token');
        if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }
    }
    
    /**
     * Obtener header HTTP
     */
    protected function getHeader($name) {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$name] ?? null;
    }
    
    /**
     * Establecer mensaje flash
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
    
    /**
     * Obtener y limpiar mensaje flash
     */
    protected function getFlash() {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}
