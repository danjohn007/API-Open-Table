<?php $pageTitle = htmlspecialchars($restaurant['name']); ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/restaurants" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Restaurantes
    </a>
</div>

<!-- Header del restaurante -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="h-48 bg-gradient-to-br from-primary to-secondary relative">
        <?php if ($restaurant['cover_image']): ?>
        <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($restaurant['cover_image']) ?>" 
             alt="<?= htmlspecialchars($restaurant['name']) ?>" 
             class="w-full h-full object-cover">
        <?php endif; ?>
    </div>
    <div class="p-6 -mt-16 relative">
        <div class="flex items-end justify-between">
            <div class="flex items-end space-x-4">
                <div class="w-24 h-24 bg-white rounded-xl shadow-lg flex items-center justify-center border-4 border-white">
                    <?php if ($restaurant['logo']): ?>
                    <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($restaurant['logo']) ?>" 
                         alt="Logo" class="w-full h-full object-cover rounded-lg">
                    <?php else: ?>
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="pb-2">
                    <h1 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($restaurant['name']) ?></h1>
                    <p class="text-gray-500"><?= htmlspecialchars($restaurant['city']) ?>, <?= htmlspecialchars($restaurant['state']) ?></p>
                </div>
            </div>
            <div class="flex space-x-2">
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/restaurants/<?= $restaurant['id'] ?>/edit" 
                   class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                    Editar
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Columna principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Información general -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información General</h3>
            
            <?php if ($restaurant['description']): ?>
            <p class="text-gray-600 mb-4"><?= nl2br(htmlspecialchars($restaurant['description'])) ?></p>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span><?= htmlspecialchars($restaurant['address']) ?></span>
                </div>
                
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span><?= htmlspecialchars($restaurant['phone']) ?></span>
                </div>
                
                <?php if ($restaurant['email']): ?>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span><?= htmlspecialchars($restaurant['email']) ?></span>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?= date('H:i', strtotime($restaurant['opening_time'])) ?> - <?= date('H:i', strtotime($restaurant['closing_time'])) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Áreas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Áreas del Restaurante</h3>
                <a href="<?= BASE_URL ?>/admin/restaurants/<?= $restaurant['id'] ?>/areas" class="text-primary hover:text-secondary text-sm font-medium">
                    Gestionar áreas →
                </a>
            </div>
            
            <?php if (empty($areas)): ?>
            <p class="text-gray-500">No hay áreas configuradas</p>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($areas as $area): ?>
                <div class="p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <h4 class="font-medium text-gray-800"><?= htmlspecialchars($area['name']) ?></h4>
                        <div class="flex space-x-1">
                            <?php if ($area['is_outdoor']): ?>
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded">Exterior</span>
                            <?php endif; ?>
                            <?php if ($area['is_vip']): ?>
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">VIP</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($area['description']): ?>
                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($area['description']) ?></p>
                    <?php endif; ?>
                    <?php if ($area['surcharge'] > 0): ?>
                    <p class="text-sm text-orange-600 mt-1">+$<?= number_format($area['surcharge'], 2) ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Mesas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Mesas</h3>
                <a href="<?= BASE_URL ?>/admin/tables?restaurant_id=<?= $restaurant['id'] ?>" class="text-primary hover:text-secondary text-sm font-medium">
                    Ver todas las mesas →
                </a>
            </div>
            
            <?php if (empty($tables)): ?>
            <p class="text-gray-500">No hay mesas configuradas</p>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-4 py-2">Mesa</th>
                            <th class="px-4 py-2">Área</th>
                            <th class="px-4 py-2">Capacidad</th>
                            <th class="px-4 py-2">Forma</th>
                            <th class="px-4 py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach (array_slice($tables, 0, 10) as $table): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($table['table_number']) ?></td>
                            <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($table['area_name'] ?? 'Sin área') ?></td>
                            <td class="px-4 py-3 text-gray-600"><?= $table['min_capacity'] ?> - <?= $table['capacity'] ?> personas</td>
                            <td class="px-4 py-3 text-gray-600"><?= ucfirst($table['shape']) ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full <?= $table['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                                    <?= $table['is_active'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Columna lateral -->
    <div class="space-y-6">
        <!-- Estadísticas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Mesas</span>
                    <span class="font-semibold text-gray-800"><?= $stats['tables_count'] ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Capacidad total</span>
                    <span class="font-semibold text-gray-800"><?= $stats['total_capacity'] ?> personas</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Reservaciones hoy</span>
                    <span class="font-semibold text-gray-800"><?= $stats['today_reservations'] ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Pendientes</span>
                    <span class="font-semibold text-yellow-600"><?= $stats['pending_reservations'] ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Este mes</span>
                    <span class="font-semibold text-gray-800"><?= $stats['monthly_reservations'] ?></span>
                </div>
            </div>
        </div>
        
        <!-- Horarios -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Horarios</h3>
            <div class="space-y-2">
                <?php 
                $dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                foreach ($schedules as $schedule): 
                ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <span class="text-gray-600"><?= $dayNames[$schedule['day_of_week']] ?></span>
                    <?php if ($schedule['is_closed']): ?>
                    <span class="text-red-500 text-sm">Cerrado</span>
                    <?php else: ?>
                    <span class="text-gray-800 text-sm">
                        <?= date('H:i', strtotime($schedule['opening_time'])) ?> - 
                        <?= date('H:i', strtotime($schedule['closing_time'])) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
            <div class="space-y-2">
                <a href="<?= BASE_URL ?>/admin/reservations?restaurant_id=<?= $restaurant['id'] ?>" 
                   class="flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Ver Reservaciones
                </a>
                <a href="<?= BASE_URL ?>/admin/reservations/calendar?restaurant_id=<?= $restaurant['id'] ?>" 
                   class="flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Calendario
                </a>
                <a href="<?= BASE_URL ?>/admin/tables/map?restaurant_id=<?= $restaurant['id'] ?>" 
                   class="flex items-center px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    Mapa de Mesas
                </a>
            </div>
        </div>
    </div>
</div>
