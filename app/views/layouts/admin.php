<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Panel de Administración</title>
    
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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- FullCalendar -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { @apply flex items-center px-4 py-2.5 text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-colors; }
        .sidebar-link.active { @apply bg-primary text-white hover:bg-primary hover:text-white; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="flex min-h-screen">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="fixed inset-y-0 left-0 z-50 bg-white border-r border-gray-200 transition-all duration-300 hidden lg:block">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                    <a href="<?= BASE_URL ?>/admin/dashboard" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="text-lg font-bold text-gray-800">Reservaciones</span>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="p-1 rounded hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Dashboard</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/reservations" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/reservations') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Reservaciones</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/reservations/calendar" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/calendar') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Calendario</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/restaurants" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/restaurants') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Restaurantes</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/tables" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/tables') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Mesas</span>
                    </a>
                    
                    <a href="<?= BASE_URL ?>/admin/customers" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/customers') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Clientes</span>
                    </a>
                    
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <span x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase">Administración</span>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/admin/settings" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/settings') !== false ? 'active' : '' ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span x-show="sidebarOpen" class="ml-3">Configuración</span>
                    </a>
                    <?php endif; ?>
                </nav>
                
                <!-- User Info -->
                <div class="p-4 border-t border-gray-200">
                    <div x-show="sidebarOpen" class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-medium">
                                <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></p>
                            <p class="text-xs text-gray-500 truncate"><?= ucfirst($_SESSION['user_role'] ?? 'user') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Mobile sidebar backdrop -->
        <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-cloak></div>
        
        <!-- Mobile sidebar -->
        <aside x-show="mobileMenuOpen" x-cloak class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 lg:hidden">
            <!-- Same content as desktop sidebar -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                <a href="<?= BASE_URL ?>/admin/dashboard" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-lg font-bold text-gray-800">Reservaciones</span>
                </a>
                <button @click="mobileMenuOpen = false" class="p-1 rounded hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="px-3 py-4 space-y-1">
                <a href="<?= BASE_URL ?>/admin/dashboard" class="sidebar-link">Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/reservations" class="sidebar-link">Reservaciones</a>
                <a href="<?= BASE_URL ?>/admin/restaurants" class="sidebar-link">Restaurantes</a>
                <a href="<?= BASE_URL ?>/admin/tables" class="sidebar-link">Mesas</a>
                <a href="<?= BASE_URL ?>/admin/customers" class="sidebar-link">Clientes</a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/settings" class="sidebar-link">Configuración</a>
                <?php endif; ?>
            </nav>
        </aside>
        
        <!-- Main content -->
        <div :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'" class="flex-1 transition-all duration-300">
            <!-- Top navbar -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                    <div class="flex items-center space-x-4">
                        <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800"><?= $pageTitle ?? 'Panel de Control' ?></h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Quick actions -->
                        <a href="<?= BASE_URL ?>/admin/reservations/create" class="hidden sm:flex items-center px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-secondary transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nueva Reservación
                        </a>
                        
                        <!-- User menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-medium">
                                    <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                <a href="<?= BASE_URL ?>/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mi Perfil</a>
                                <a href="<?= BASE_URL ?>/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash messages -->
            <?php $flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); ?>
            <?php if ($flash): ?>
            <div class="px-4 lg:px-6 pt-4">
                <div class="p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Page content -->
            <main class="p-4 lg:p-6">
                <?= $content ?>
            </main>
        </div>
    </div>
    
    <!-- Common Scripts -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        
        // Helper function for API calls
        async function apiCall(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            
            const response = await fetch(BASE_URL + url, { ...defaultOptions, ...options });
            return response.json();
        }
    </script>
</body>
</html>
