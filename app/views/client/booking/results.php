<?php $pageTitle = 'Disponibilidad - ' . htmlspecialchars($restaurant['name']); ?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/reservar" class="inline-flex items-center text-gray-600 hover:text-primary">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a buscar
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header del restaurante -->
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($restaurant['name']) ?></h1>
            <p class="text-gray-600"><?= htmlspecialchars($restaurant['address']) ?>, <?= htmlspecialchars($restaurant['city']) ?></p>
        </div>
        
        <!-- Resumen de búsqueda -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-wrap items-center gap-6 text-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium"><?= date('l, d F Y', strtotime($date)) ?></span>
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="font-medium"><?= $partySize ?> <?= $partySize === 1 ? 'persona' : 'personas' ?></span>
                </div>
            </div>
        </div>
        
        <!-- Resultados -->
        <div class="p-6">
            <?php if ($isClosed): ?>
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Restaurante cerrado</h3>
                <p class="text-gray-500">El restaurante está cerrado el día seleccionado.</p>
                <a href="<?= BASE_URL ?>/reservar" class="mt-4 inline-flex items-center text-primary hover:text-secondary">
                    Buscar otra fecha
                </a>
            </div>
            <?php elseif (empty($slots)): ?>
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sin disponibilidad</h3>
                <p class="text-gray-500">No hay mesas disponibles para el día y número de personas seleccionados.</p>
                <a href="<?= BASE_URL ?>/reservar" class="mt-4 inline-flex items-center text-primary hover:text-secondary">
                    Buscar otra fecha u horario
                </a>
            </div>
            <?php else: ?>
            <h3 class="text-lg font-medium text-gray-800 mb-4">Horarios disponibles</h3>
            <p class="text-gray-600 mb-6">Selecciona el horario de tu preferencia:</p>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                <?php foreach ($slots as $slot): ?>
                <a href="<?= BASE_URL ?>/reservar/formulario?restaurant_id=<?= $restaurant['id'] ?>&date=<?= $date ?>&time=<?= $slot['time'] ?>&party_size=<?= $partySize ?>" 
                   class="block text-center px-4 py-3 border-2 border-primary text-primary font-medium rounded-lg hover:bg-primary hover:text-white transition-colors">
                    <?= $slot['time'] ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
