<?php $pageTitle = 'Reservación Confirmada'; ?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header de confirmación -->
        <div class="p-8 bg-green-50 text-center">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">¡Reservación Confirmada!</h1>
            <p class="text-gray-600">Tu mesa ha sido reservada exitosamente.</p>
        </div>
        
        <!-- Código de confirmación -->
        <div class="p-6 bg-gray-50 border-b border-gray-200 text-center">
            <p class="text-sm text-gray-500 mb-1">Código de confirmación</p>
            <p class="text-3xl font-bold text-primary font-mono"><?= htmlspecialchars($reservation['confirmation_code']) ?></p>
            <p class="text-xs text-gray-500 mt-2">Guarda este código para consultar o modificar tu reservación</p>
        </div>
        
        <!-- Detalles de la reservación -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Detalles de tu Reservación</h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($reservation['restaurant_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($reservation['restaurant_address']) ?></p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800">
                            <?= date('l, d F Y', strtotime($reservation['reservation_date'])) ?>
                        </p>
                        <p class="text-sm text-gray-500">a las <?= date('H:i', strtotime($reservation['reservation_time'])) ?> hrs</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="font-medium text-gray-800"><?= $reservation['party_size'] ?> <?= $reservation['party_size'] == 1 ? 'persona' : 'personas' ?></p>
                </div>
                
                <?php if ($reservation['table_number']): ?>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <p class="font-medium text-gray-800">Mesa <?= htmlspecialchars($reservation['table_number']) ?>
                        <?php if ($reservation['area_name']): ?>
                            <span class="text-gray-500 font-normal">(<?= htmlspecialchars($reservation['area_name']) ?>)</span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($reservation['restaurant_phone']) ?></p>
                </div>
            </div>
            
            <?php if ($reservation['special_requests']): ?>
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-1">Solicitudes especiales:</p>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($reservation['special_requests']) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Información importante -->
        <div class="p-6 bg-yellow-50 border-t border-yellow-100">
            <h3 class="font-medium text-yellow-800 mb-2">Información importante</h3>
            <ul class="text-sm text-yellow-700 space-y-1">
                <li>• Por favor llega 10 minutos antes de tu hora de reservación.</li>
                <li>• La mesa se mantendrá reservada por 15 minutos después de la hora indicada.</li>
                <li>• Si necesitas cancelar, hazlo con al menos 24 horas de anticipación.</li>
            </ul>
        </div>
        
        <!-- Acciones -->
        <div class="p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= BASE_URL ?>/reservar/modificar/<?= $reservation['confirmation_code'] ?>" 
                   class="flex-1 text-center px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Modificar Reservación
                </a>
                <a href="<?= BASE_URL ?>/reservar/cancelar/<?= $reservation['confirmation_code'] ?>" 
                   class="flex-1 text-center px-4 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                    Cancelar Reservación
                </a>
            </div>
            
            <div class="mt-4 text-center">
                <a href="<?= BASE_URL ?>" class="text-primary hover:text-secondary">
                    ← Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>
