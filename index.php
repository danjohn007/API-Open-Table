<?php
/**
 * Sistema de Reservaciones de Mesas con OpenTable
 * Punto de entrada principal
 */

// Iniciar sesión
session_start();

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Cargar router
require_once ROOT_PATH . '/core/Router.php';

// Crear instancia del router
$router = new Router();

// Definir rutas

// Auth routes
$router->add('login', ['controller' => 'auth', 'action' => 'login']);
$router->add('logout', ['controller' => 'auth', 'action' => 'logout']);
$router->add('register', ['controller' => 'auth', 'action' => 'register']);
$router->add('forgot-password', ['controller' => 'auth', 'action' => 'forgot-password']);
$router->add('admin/profile', ['controller' => 'auth', 'action' => 'profile']);

// Home routes
$router->add('', ['controller' => 'home', 'action' => 'index']);
$router->add('restaurante/{slug}', ['controller' => 'home', 'action' => 'restaurant']);
$router->add('terminos', ['controller' => 'home', 'action' => 'terms']);

// Client booking routes
$router->add('reservar', ['controller' => 'booking', 'action' => 'index']);
$router->add('reservar/buscar', ['controller' => 'booking', 'action' => 'search']);
$router->add('reservar/formulario', ['controller' => 'booking', 'action' => 'form']);
$router->add('reservar/procesar', ['controller' => 'booking', 'action' => 'process']);
$router->add('reservar/confirmacion/{code}', ['controller' => 'booking', 'action' => 'confirmation']);
$router->add('reservar/consultar', ['controller' => 'booking', 'action' => 'lookup']);
$router->add('reservar/modificar/{code}', ['controller' => 'booking', 'action' => 'modify']);
$router->add('reservar/cancelar/{code}', ['controller' => 'booking', 'action' => 'cancel']);
$router->add('api/disponibilidad', ['controller' => 'booking', 'action' => 'api-availability']);

// Admin routes
$router->add('admin', ['controller' => 'dashboard', 'action' => 'index']);
$router->add('admin/dashboard', ['controller' => 'dashboard', 'action' => 'index']);
$router->add('admin/dashboard/select-restaurant', ['controller' => 'dashboard', 'action' => 'select-restaurant']);

// Admin - Restaurants
$router->add('admin/restaurants', ['controller' => 'restaurants', 'action' => 'index']);
$router->add('admin/restaurants/create', ['controller' => 'restaurants', 'action' => 'create']);
$router->add('admin/restaurants/{id:\d+}', ['controller' => 'restaurants', 'action' => 'show']);
$router->add('admin/restaurants/{id:\d+}/edit', ['controller' => 'restaurants', 'action' => 'edit']);
$router->add('admin/restaurants/{id:\d+}/delete', ['controller' => 'restaurants', 'action' => 'delete']);
$router->add('admin/restaurants/{restaurant_id:\d+}/areas', ['controller' => 'tables', 'action' => 'areas']);

// Admin - Tables
$router->add('admin/tables', ['controller' => 'tables', 'action' => 'index']);
$router->add('admin/tables/create', ['controller' => 'tables', 'action' => 'create']);
$router->add('admin/tables/{id:\d+}/edit', ['controller' => 'tables', 'action' => 'edit']);
$router->add('admin/tables/{id:\d+}/delete', ['controller' => 'tables', 'action' => 'delete']);
$router->add('admin/tables/availability', ['controller' => 'tables', 'action' => 'availability']);
$router->add('admin/tables/map', ['controller' => 'tables', 'action' => 'map']);
$router->add('admin/tables/block', ['controller' => 'tables', 'action' => 'block']);
$router->add('admin/tables/unblock', ['controller' => 'tables', 'action' => 'unblock']);

// Admin - Reservations
$router->add('admin/reservations', ['controller' => 'reservations', 'action' => 'index']);
$router->add('admin/reservations/create', ['controller' => 'reservations', 'action' => 'create']);
$router->add('admin/reservations/calendar', ['controller' => 'reservations', 'action' => 'calendar']);
$router->add('admin/reservations/calendar-events', ['controller' => 'reservations', 'action' => 'calendar-events']);
$router->add('admin/reservations/search-availability', ['controller' => 'reservations', 'action' => 'search-availability']);
$router->add('admin/reservations/change-status', ['controller' => 'reservations', 'action' => 'change-status']);
$router->add('admin/reservations/check-in', ['controller' => 'reservations', 'action' => 'check-in']);
$router->add('admin/reservations/assign-table', ['controller' => 'reservations', 'action' => 'assign-table']);
$router->add('admin/reservations/cancel', ['controller' => 'reservations', 'action' => 'cancel']);
$router->add('admin/reservations/{id:\d+}', ['controller' => 'reservations', 'action' => 'show']);
$router->add('admin/reservations/{id:\d+}/edit', ['controller' => 'reservations', 'action' => 'edit']);

// Admin - Customers
$router->add('admin/customers', ['controller' => 'customers', 'action' => 'index']);
$router->add('admin/customers/create', ['controller' => 'customers', 'action' => 'create']);
$router->add('admin/customers/search', ['controller' => 'customers', 'action' => 'search']);
$router->add('admin/customers/toggle-vip', ['controller' => 'customers', 'action' => 'toggle-vip']);
$router->add('admin/customers/{id:\d+}', ['controller' => 'customers', 'action' => 'show']);
$router->add('admin/customers/{id:\d+}/edit', ['controller' => 'customers', 'action' => 'edit']);

// Admin - Settings
$router->add('admin/settings', ['controller' => 'settings', 'action' => 'index']);
$router->add('admin/settings/appearance', ['controller' => 'settings', 'action' => 'appearance']);
$router->add('admin/settings/mail', ['controller' => 'settings', 'action' => 'mail']);
$router->add('admin/settings/payment', ['controller' => 'settings', 'action' => 'payment']);
$router->add('admin/settings/opentable', ['controller' => 'settings', 'action' => 'opentable']);
$router->add('admin/settings/integrations', ['controller' => 'settings', 'action' => 'integrations']);
$router->add('admin/settings/policies', ['controller' => 'settings', 'action' => 'policies']);
$router->add('admin/settings/users', ['controller' => 'settings', 'action' => 'users']);
$router->add('admin/settings/test-email', ['controller' => 'settings', 'action' => 'test-email']);
$router->add('admin/settings/test-opentable', ['controller' => 'settings', 'action' => 'test-opentable']);

// Obtener la URL actual
$url = $_GET['url'] ?? '';

// Eliminar la barra diagonal final si existe
$url = rtrim($url, '/');

// Si el URL viene desde REQUEST_URI (sin mod_rewrite), extraer la ruta correcta
if (empty($url) && isset($_SERVER['REQUEST_URI'])) {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $scriptDir = dirname($scriptName);
    
    // Eliminar el directorio base de la URI
    if ($scriptDir !== '/' && $scriptDir !== '\\') {
        $url = substr($requestUri, strlen($scriptDir));
    } else {
        $url = $requestUri;
    }
    
    // Eliminar barras diagonales del inicio y final
    $url = trim($url, '/');
    
    // Eliminar index.php del inicio si existe
    if (strpos($url, 'index.php') === 0) {
        $url = substr($url, strlen('index.php'));
        $url = ltrim($url, '/');
    }
}

// Despachar la ruta
try {
    $router->dispatch($url);
} catch (Exception $e) {
    // Manejar errores - nunca exponer información sensible del sistema
    if (APP_DEBUG) {
        // En desarrollo, mostrar detalles pero sanitizados
        http_response_code($e->getCode() === 404 ? 404 : 500);
        echo '<h1>Error de Desarrollo</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        // Solo mostrar nombre de archivo, no rutas completas
        echo '<pre>' . htmlspecialchars(preg_replace('/\/.*\//', '.../', $e->getTraceAsString())) . '</pre>';
    } else {
        if ($e->getCode() === 404) {
            http_response_code(404);
            include APP_PATH . '/views/errors/404.php';
        } else {
            http_response_code(500);
            // Registrar error en log
            error_log('Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            include APP_PATH . '/views/errors/500.php';
        }
    }
}
