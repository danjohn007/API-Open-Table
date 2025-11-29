<?php $pageTitle = 'Consultar Reservación'; ?>

<div class="max-w-xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Consultar Reservación</h1>
        <p class="text-gray-600">Ingresa tu código de confirmación para ver tu reservación</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
        <form action="<?= BASE_URL ?>/reservar/consultar" method="GET" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Código de confirmación</label>
                <input type="text" name="code" value="<?= htmlspecialchars($code ?? '') ?>" required 
                       placeholder="Ej: RES123ABC"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-lg text-center uppercase">
            </div>
            
            <button type="submit" class="w-full py-3 bg-primary text-white font-semibold rounded-lg hover:bg-secondary transition-colors">
                Buscar Reservación
            </button>
        </form>
        
        <?php if (isset($error)): ?>
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-center"><?= htmlspecialchars($error) ?></p>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (isset($reservation) && $reservation): ?>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mt-8">
        <!-- Estado de la reservación -->
        <div class="p-6 text-center border-b border-gray-200
            <?php 
            switch($reservation['status']) {
                case 'confirmed': echo 'bg-green-50'; break;
                case 'pending': echo 'bg-yellow-50'; break;
                case 'cancelled': echo 'bg-red-50'; break;
                case 'completed': echo 'bg-gray-50'; break;
                default: echo 'bg-gray-50';
            }
            ?>">
            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium
                <?php 
                switch($reservation['status']) {
                    case 'confirmed': echo 'bg-green-100 text-green-700'; break;
                    case 'pending': echo 'bg-yellow-100 text-yellow-700'; break;
                    case 'cancelled': echo 'bg-red-100 text-red-700'; break;
                    case 'completed': echo 'bg-gray-100 text-gray-700'; break;
                    case 'seated': echo 'bg-purple-100 text-purple-700'; break;
                    case 'no_show': echo 'bg-orange-100 text-orange-700'; break;
                    default: echo 'bg-gray-100 text-gray-700';
                }
                ?>">
                <?php 
                $statusLabels = [
                    'pending' => 'Pendiente de confirmación',
                    'confirmed' => 'Confirmada',
                    'waiting' => 'En espera',
                    'seated' => 'Cliente sentado',
                    'completed' => 'Completada',
                    'cancelled' => 'Cancelada',
                    'no_show' => 'No se presentó'
                ];
                echo $statusLabels[$reservation['status']] ?? ucfirst($reservation['status']);
                ?>
            </span>
        </div>
        
        <!-- Detalles -->
        <div class="p-6">
            <div class="mb-4 text-center">
                <p class="text-sm text-gray-500">Código de confirmación</p>
                <p class="text-2xl font-bold text-primary font-mono"><?= htmlspecialchars($reservation['confirmation_code']) ?></p>
            </div>
            
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
                
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <p class="text-gray-800"><?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Acciones -->
        <?php if (!in_array($reservation['status'], ['cancelled', 'completed', 'no_show', 'seated'])): ?>
        <div class="p-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= BASE_URL ?>/reservar/modificar/<?= $reservation['confirmation_code'] ?>" 
                   class="flex-1 text-center px-4 py-3 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                    Modificar
                </a>
                <a href="<?= BASE_URL ?>/reservar/cancelar/<?= $reservation['confirmation_code'] ?>" 
                   class="flex-1 text-center px-4 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
