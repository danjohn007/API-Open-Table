<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Sistema de Reservaciones</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e40af',
                        accent: '#3b82f6'
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?= BASE_URL ?>" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-800">Reservaciones</span>
                    </a>
                </div>
                
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="<?= BASE_URL ?>" class="text-gray-600 hover:text-primary transition-colors">Inicio</a>
                    <a href="<?= BASE_URL ?>/reservar" class="text-gray-600 hover:text-primary transition-colors">Reservar</a>
                    <a href="<?= BASE_URL ?>/reservar/consultar" class="text-gray-600 hover:text-primary transition-colors">Consultar Reservación</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/admin/dashboard" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-secondary transition-colors">
                            Panel de Control
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-secondary transition-colors">
                            Iniciar Sesión
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Mobile menu -->
                    <div x-show="open" x-cloak @click.away="open = false" class="absolute top-16 left-0 right-0 bg-white border-b shadow-lg">
                        <div class="px-4 py-3 space-y-2">
                            <a href="<?= BASE_URL ?>" class="block py-2 text-gray-600">Inicio</a>
                            <a href="<?= BASE_URL ?>/reservar" class="block py-2 text-gray-600">Reservar</a>
                            <a href="<?= BASE_URL ?>/reservar/consultar" class="block py-2 text-gray-600">Consultar Reservación</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="<?= BASE_URL ?>/admin/dashboard" class="block py-2 text-primary font-medium">Panel de Control</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL ?>/login" class="block py-2 text-primary font-medium">Iniciar Sesión</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Flash messages -->
    <?php $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); ?>
    <?php if ($flash): ?>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Main content -->
    <main>
        <?= $content ?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Sistema de Reservaciones</h3>
                    <p class="text-gray-400 text-sm">
                        Plataforma integral para la gestión de reservaciones de restaurantes con integración OpenTable.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="<?= BASE_URL ?>" class="hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="<?= BASE_URL ?>/reservar" class="hover:text-white transition-colors">Hacer Reservación</a></li>
                        <li><a href="<?= BASE_URL ?>/reservar/consultar" class="hover:text-white transition-colors">Consultar Reservación</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li>Email: contacto@ejemplo.com</li>
                        <li>Tel: +52 442 123 4567</li>
                        <li>Querétaro, México</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; <?= date('Y') ?> Sistema de Reservaciones. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
    </script>
</body>
</html>
