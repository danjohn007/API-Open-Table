<?php $pageTitle = 'Mesas - ' . htmlspecialchars($restaurant['name']); ?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Mesas</h2>
        <p class="text-gray-600"><?= htmlspecialchars($restaurant['name']) ?></p>
    </div>
    <div class="flex items-center space-x-4">
        <a href="<?= BASE_URL ?>/admin/tables/map?restaurant_id=<?= $restaurant['id'] ?>" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
            </svg>
            Mapa
        </a>
        <a href="<?= BASE_URL ?>/admin/restaurants/<?= $restaurant['id'] ?>/areas" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            Gestionar Áreas
        </a>
        <a href="<?= BASE_URL ?>/admin/tables/create?restaurant_id=<?= $restaurant['id'] ?>" 
           class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Mesa
        </a>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-sm text-gray-500">Total Mesas</p>
        <p class="text-2xl font-bold text-gray-800"><?= count($tables) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-sm text-gray-500">Capacidad Total</p>
        <p class="text-2xl font-bold text-gray-800"><?= array_sum(array_column($tables, 'capacity')) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-sm text-gray-500">Áreas</p>
        <p class="text-2xl font-bold text-gray-800"><?= count($areas) ?></p>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4">
        <p class="text-sm text-gray-500">Mesas Activas</p>
        <p class="text-2xl font-bold text-gray-800"><?= count(array_filter($tables, fn($t) => $t['is_active'])) ?></p>
    </div>
</div>

<!-- Lista de mesas -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <?php if (empty($tables)): ?>
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900">No hay mesas</h3>
        <p class="mt-2 text-gray-500">Comienza agregando mesas al restaurante.</p>
        <a href="<?= BASE_URL ?>/admin/tables/create?restaurant_id=<?= $restaurant['id'] ?>" 
           class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Agregar Mesa
        </a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Mesa</th>
                    <th class="px-6 py-3">Área</th>
                    <th class="px-6 py-3">Capacidad</th>
                    <th class="px-6 py-3">Forma</th>
                    <th class="px-6 py-3">Características</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($tables as $table): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($table['table_number']) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($table['area_name']): ?>
                        <span class="text-gray-600"><?= htmlspecialchars($table['area_name']) ?></span>
                        <?php if ($table['is_outdoor']): ?>
                        <span class="ml-1 text-xs text-green-600">🌳</span>
                        <?php endif; ?>
                        <?php if ($table['is_vip']): ?>
                        <span class="ml-1 text-xs text-purple-600">⭐</span>
                        <?php endif; ?>
                        <?php else: ?>
                        <span class="text-gray-400">Sin área</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?= $table['min_capacity'] ?> - <?= $table['capacity'] ?> personas
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php 
                        $shapes = ['round' => 'Redonda', 'square' => 'Cuadrada', 'rectangular' => 'Rectangular', 'other' => 'Otra'];
                        echo $shapes[$table['shape']] ?? $table['shape'];
                        ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($table['is_combinable']): ?>
                        <span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-700 rounded">Combinable</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full <?= $table['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                            <?= $table['is_active'] ? 'Activa' : 'Inactiva' ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="<?= BASE_URL ?>/admin/tables/<?= $table['id'] ?>/edit" 
                               class="p-1 text-gray-400 hover:text-primary transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
