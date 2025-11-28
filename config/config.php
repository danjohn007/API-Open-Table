<?php
/**
 * Sistema de Reservaciones de Mesas con OpenTable
 * Archivo de configuración principal
 */

// Detectar automáticamente la URL base
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim($scriptPath, '/');

// Si estamos en el subdirectorio public, subir un nivel
if (basename($basePath) === 'public') {
    $basePath = dirname($basePath);
}

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('PUBLIC_URL', BASE_URL . '/public');

// Rutas del sistema
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'opentable_reservations');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Reservaciones');
define('APP_VERSION', '1.0.0');
// IMPORTANTE: Cambiar a false en producción
define('APP_DEBUG', getenv('APP_DEBUG') !== false ? getenv('APP_DEBUG') === 'true' : true);

// Configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Configuración de OpenTable API
define('OPENTABLE_API_URL', 'https://platform.opentable.com');
define('OPENTABLE_API_KEY', '');
define('OPENTABLE_API_SECRET', '');

// Configuración de correo
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USER', '');
define('MAIL_PASS', '');
define('MAIL_FROM', 'noreply@example.com');
define('MAIL_FROM_NAME', 'Sistema de Reservaciones');

// Configuración de PayPal
define('PAYPAL_CLIENT_ID', '');
define('PAYPAL_SECRET', '');
define('PAYPAL_MODE', 'sandbox'); // sandbox o live

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        ROOT_PATH . '/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
