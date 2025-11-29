<?php $pageTitle = 'Hacer Reservación'; ?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Hacer Reservación</h1>
        <p class="text-gray-600">Selecciona el restaurante, fecha y número de personas</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
        <form action="<?= BASE_URL ?>/reservar/buscar" method="GET" class="space-y-6">
            <!-- Restaurante -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Restaurante</label>
                <select name="restaurant_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-lg">
                    <option value="">Selecciona un restaurante</option>
                    <?php foreach ($restaurants as $restaurant): ?>
                    <option value="<?= $restaurant['id'] ?>">
                        <?= htmlspecialchars($restaurant['name']) ?> - <?= htmlspecialchars($restaurant['city']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Fecha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="date" required 
                           min="<?= date('Y-m-d') ?>" 
                           max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                           value="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-lg">
                </div>
                
                <!-- Hora preferida -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hora preferida</label>
                    <select name="time" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-lg">
                        <?php for ($h = 12; $h <= 22; $h++): ?>
                            <?php for ($m = 0; $m < 60; $m += 30): ?>
                                <?php $time = sprintf('%02d:%02d', $h, $m); ?>
                                <option value="<?= $time ?>" <?= $time === '19:00' ? 'selected' : '' ?>><?= $time ?></option>
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <!-- Personas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de personas</label>
                    <select name="party_size" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary text-lg">
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                        <option value="<?= $i ?>" <?= $i === 2 ? 'selected' : '' ?>>
                            <?= $i ?> <?= $i === 1 ? 'persona' : 'personas' ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="w-full py-4 bg-primary text-white text-lg font-semibold rounded-lg hover:bg-secondary transition-colors">
                Buscar Disponibilidad
            </button>
        </form>
    </div>
    
    <!-- Info adicional -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-medium text-gray-800">Confirmación inmediata</h3>
                <p class="text-sm text-gray-600">Recibe tu confirmación al instante por correo electrónico.</p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-medium text-gray-800">Sin cargos</h3>
                <p class="text-sm text-gray-600">Reservar es completamente gratis.</p>
            </div>
        </div>
        
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-medium text-gray-800">Cancelación fácil</h3>
                <p class="text-sm text-gray-600">Modifica o cancela tu reservación cuando quieras.</p>
            </div>
        </div>
    </div>
</div>
