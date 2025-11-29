<?php $pageTitle = 'Cancelar Reservación'; ?>

<div class="max-w-xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Cancelar Reservación</h1>
        <p class="text-gray-600">¿Estás seguro de que deseas cancelar esta reservación?</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Detalles de la reservación -->
        <div class="p-6">
            <div class="mb-4 text-center">
                <p class="text-sm text-gray-500">Código de confirmación</p>
                <p class="text-2xl font-bold text-primary font-mono"><?= htmlspecialchars($reservation['confirmation_code']) ?></p>
            </div>
            
            <div class="space-y-4 mt-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-800"><?= htmlspecialchars($reservation['restaurant_name']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($reservation['restaurant_address'] ?? '') ?></p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-800">
                        <?= date('l, d F Y', strtotime($reservation['reservation_date'])) ?> 
                        a las <?= date('H:i', strtotime($reservation['reservation_time'])) ?>
                    </p>
                </div>
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-800"><?= $reservation['party_size'] ?> personas</p>
                </div>
            </div>
        </div>
        
        <!-- Advertencia -->
        <div class="p-6 bg-red-50 border-t border-red-100">
            <p class="text-sm text-red-700">
                <strong>Importante:</strong> Esta acción no se puede deshacer. 
                Una vez cancelada, deberás crear una nueva reservación si cambias de opinión.
            </p>
        </div>
        
        <!-- Botones de acción -->
        <div class="p-6 border-t border-gray-200">
            <form action="<?= BASE_URL ?>/reservar/cancelar/<?= htmlspecialchars($reservation['confirmation_code']) ?>" method="POST">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Sí, Cancelar Reservación
                    </button>
                    <a href="<?= BASE_URL ?>/reservar/consultar?code=<?= htmlspecialchars($reservation['confirmation_code']) ?>" 
                       class="flex-1 text-center py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        No, Mantener Reservación
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Enlace para volver -->
    <div class="mt-6 text-center">
        <a href="<?= BASE_URL ?>/reservar/consultar?code=<?= htmlspecialchars($reservation['confirmation_code']) ?>" 
           class="text-primary hover:text-secondary">
            ← Volver a detalles de la reservación
        </a>
    </div>
</div>
