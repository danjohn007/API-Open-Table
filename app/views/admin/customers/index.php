<?php $pageTitle = 'Clientes'; ?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Clientes</h2>
        <p class="text-gray-600">Gestión de clientes del sistema</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/customers/create" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Cliente
    </a>
</div>

<!-- Buscador -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                   placeholder="Buscar por nombre, email o teléfono..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
            Buscar
        </button>
        <?php if ($search): ?>
        <a href="<?= BASE_URL ?>/admin/customers" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            Limpiar
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Lista de clientes -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <?php if (empty($customers)): ?>
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900">No hay clientes</h3>
        <p class="mt-2 text-gray-500">
            <?= $search ? 'No se encontraron clientes con esos criterios.' : 'Los clientes se crearán automáticamente al hacer reservaciones.' ?>
        </p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Contacto</th>
                    <th class="px-6 py-3">Visitas</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($customers as $customer): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium mr-3">
                                <?= strtoupper(substr($customer['first_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>
                                    <?php if ($customer['vip_status']): ?>
                                    <span class="ml-1 px-1.5 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">VIP</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900"><?= htmlspecialchars($customer['email']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($customer['phone']) ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <p class="text-gray-900"><?= $customer['total_visits'] ?> visitas</p>
                            <?php if ($customer['total_no_shows'] > 0): ?>
                            <p class="text-red-500"><?= $customer['total_no_shows'] ?> no shows</p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($customer['total_no_shows'] >= 3): ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Alto riesgo</span>
                        <?php elseif ($customer['vip_status']): ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">VIP</span>
                        <?php else: ?>
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Normal</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>" 
                               class="p-1 text-gray-400 hover:text-primary transition-colors" title="Ver detalles">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>/edit" 
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
    
    <!-- Paginación -->
    <?php if (isset($pagination) && $pagination && $pagination['total_pages'] > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-600">
                Mostrando <?= count($customers) ?> de <?= $pagination['total'] ?> clientes
            </p>
            <div class="flex space-x-2">
                <?php if ($pagination['current_page'] > 1): ?>
                <a href="?page=<?= $pagination['current_page'] - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                   class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Anterior
                </a>
                <?php endif; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <a href="?page=<?= $pagination['current_page'] + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>" 
                   class="px-3 py-1 border border-gray-300 rounded text-gray-600 hover:bg-gray-50">
                    Siguiente
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
