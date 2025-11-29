<?php $pageTitle = 'Detalles del Cliente'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/customers" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Clientes
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del cliente -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary text-2xl font-bold mr-4">
                    <?= strtoupper(substr($customer['first_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?>
                    </h2>
                    <?php if ($customer['vip_status']): ?>
                    <span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">VIP</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-3 text-sm">
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <?= htmlspecialchars($customer['email']) ?>
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <?= htmlspecialchars($customer['phone']) ?>
                </div>
            </div>
            
            <!-- Estadísticas -->
            <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t text-center">
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?= $customer['total_visits'] ?></p>
                    <p class="text-xs text-gray-500">Visitas</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?= $customer['total_cancellations'] ?></p>
                    <p class="text-xs text-gray-500">Cancelaciones</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600"><?= $customer['total_no_shows'] ?></p>
                    <p class="text-xs text-gray-500">No Shows</p>
                </div>
            </div>
            
            <?php if (!empty($customer['notes'])): ?>
            <div class="mt-6 pt-6 border-t">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Notas</h3>
                <p class="text-sm text-gray-600"><?= nl2br(htmlspecialchars($customer['notes'])) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="mt-6 pt-6 border-t flex space-x-2">
                <a href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>/edit" 
                   class="flex-1 px-4 py-2 bg-primary text-white text-center rounded-lg hover:bg-secondary transition-colors">
                    Editar
                </a>
                <a href="<?= BASE_URL ?>/admin/reservations/create?customer_id=<?= $customer['id'] ?>" 
                   class="flex-1 px-4 py-2 border border-primary text-primary text-center rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Nueva Reservación
                </a>
            </div>
        </div>
    </div>
    
    <!-- Historial de reservaciones -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Historial de Reservaciones</h3>
            </div>
            
            <?php if (empty($history)): ?>
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500">No hay reservaciones registradas</p>
            </div>
            <?php else: ?>
            <div class="divide-y divide-gray-200">
                <?php foreach ($history as $reservation): ?>
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">
                                <?= htmlspecialchars($reservation['restaurant_name'] ?? 'Restaurante') ?>
                            </p>
                            <p class="text-sm text-gray-500">
                                <?= date('d/m/Y', strtotime($reservation['reservation_date'])) ?> 
                                a las <?= substr($reservation['reservation_time'], 0, 5) ?>
                                - <?= $reservation['party_size'] ?> personas
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                switch($reservation['status']) {
                                    case 'completed': echo 'bg-green-100 text-green-700'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-700'; break;
                                    case 'no_show': echo 'bg-orange-100 text-orange-700'; break;
                                    case 'confirmed': echo 'bg-blue-100 text-blue-700'; break;
                                    default: echo 'bg-gray-100 text-gray-700';
                                }
                                ?>">
                                <?= ucfirst($reservation['status']) ?>
                            </span>
                            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>" 
                               class="text-primary hover:text-secondary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
