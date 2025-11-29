<?php
/**
 * Test de Conexión y Configuración del Sistema
 * Este archivo verifica la correcta configuración del sistema
 */

// Cargar configuración
require_once __DIR__ . '/config/config.php';

// Estilos básicos
echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión - Sistema de Reservaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">🔧 Test de Conexión</h1>
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">';

// Test 1: URL Base
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">📌 URL Base</h3>';
if (defined('BASE_URL') && !empty(BASE_URL)) {
    echo '<p class="text-green-600">✅ URL Base configurada: <code class="bg-gray-100 px-2 py-1 rounded">' . BASE_URL . '</code></p>';
} else {
    echo '<p class="text-red-600">❌ URL Base no configurada</p>';
}
echo '</div>';

// Test 2: Rutas del sistema
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">📁 Rutas del Sistema</h3>
    <ul class="text-sm space-y-1">';

$paths = [
    'ROOT_PATH' => ROOT_PATH,
    'APP_PATH' => APP_PATH,
    'CONFIG_PATH' => CONFIG_PATH,
    'PUBLIC_PATH' => PUBLIC_PATH,
    'LOGS_PATH' => LOGS_PATH
];

foreach ($paths as $name => $path) {
    $exists = is_dir($path);
    $icon = $exists ? '✅' : '⚠️';
    $color = $exists ? 'text-green-600' : 'text-yellow-600';
    echo "<li class=\"$color\">$icon $name: <code class=\"bg-gray-100 px-1 rounded\">$path</code></li>";
}
echo '</ul></div>';

// Test 3: Conexión a la base de datos
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">🗄️ Base de Datos</h3>';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo '<p class="text-green-600">✅ Conexión exitosa a la base de datos</p>';
    echo '<ul class="text-sm mt-2 space-y-1">
        <li>Host: ' . DB_HOST . '</li>
        <li>Base de datos: ' . DB_NAME . '</li>
        <li>Usuario: ' . DB_USER . '</li>
    </ul>';
    
    // Verificar tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo '<p class="text-green-600 mt-2">✅ ' . count($tables) . ' tablas encontradas</p>';
        echo '<details class="mt-2"><summary class="cursor-pointer text-sm text-primary">Ver tablas</summary>';
        echo '<ul class="text-xs mt-1 pl-4">';
        foreach ($tables as $table) {
            echo "<li>• $table</li>";
        }
        echo '</ul></details>';
    } else {
        echo '<p class="text-yellow-600 mt-2">⚠️ No hay tablas. Ejecuta el archivo database/schema.sql</p>';
    }
    
} catch (PDOException $e) {
    echo '<p class="text-red-600">❌ Error de conexión: ' . $e->getMessage() . '</p>';
    echo '<div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-sm">
        <p class="font-medium text-yellow-800">Para crear la base de datos:</p>
        <ol class="mt-2 list-decimal pl-4 text-yellow-700">
            <li>Abre phpMyAdmin o tu cliente MySQL</li>
            <li>Crea una base de datos llamada <code>' . DB_NAME . '</code></li>
            <li>Importa el archivo <code>database/schema.sql</code></li>
        </ol>
    </div>';
}
echo '</div>';

// Test 4: PHP Version
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">🐘 PHP</h3>';
$phpVersion = phpversion();
$requiredVersion = '7.4.0';
if (version_compare($phpVersion, $requiredVersion, '>=')) {
    echo '<p class="text-green-600">✅ Versión de PHP: ' . $phpVersion . ' (mínimo requerido: ' . $requiredVersion . ')</p>';
} else {
    echo '<p class="text-red-600">❌ Versión de PHP: ' . $phpVersion . ' (se requiere al menos ' . $requiredVersion . ')</p>';
}
echo '</div>';

// Test 5: Extensiones PHP
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">🔌 Extensiones PHP</h3>
    <ul class="text-sm space-y-1">';

$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring', 'session'];
foreach ($requiredExtensions as $ext) {
    $loaded = extension_loaded($ext);
    $icon = $loaded ? '✅' : '❌';
    $color = $loaded ? 'text-green-600' : 'text-red-600';
    echo "<li class=\"$color\">$icon $ext</li>";
}
echo '</ul></div>';

// Test 6: Permisos de escritura
echo '<div class="border-b pb-4">
    <h3 class="font-semibold text-gray-700 mb-2">📝 Permisos de Escritura</h3>
    <ul class="text-sm space-y-1">';

$writableDirs = [LOGS_PATH, UPLOADS_PATH];
foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $writable = is_writable($dir);
    $icon = $writable ? '✅' : '⚠️';
    $color = $writable ? 'text-green-600' : 'text-yellow-600';
    echo "<li class=\"$color\">$icon $dir</li>";
}
echo '</ul></div>';

// Test 7: mod_rewrite
echo '<div>
    <h3 class="font-semibold text-gray-700 mb-2">🔄 Apache mod_rewrite</h3>';
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo '<p class="text-green-600">✅ mod_rewrite está habilitado</p>';
    } else {
        echo '<p class="text-yellow-600">⚠️ mod_rewrite no detectado (puede estar habilitado por configuración)</p>';
    }
} else {
    echo '<p class="text-gray-500">ℹ️ No se puede verificar mod_rewrite (no es Apache o está configurado de otra manera)</p>';
}
echo '</div>';

// Enlaces útiles
echo '</div>
        
        <div class="mt-8 text-center">
            <a href="' . BASE_URL . '" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Ir al Sistema →
            </a>
        </div>
        
        <div class="mt-4 text-center text-sm text-gray-500">
            <p>Sistema de Reservaciones de Mesas con OpenTable</p>
            <p>Versión ' . APP_VERSION . '</p>
        </div>
    </div>
</body>
</html>';
