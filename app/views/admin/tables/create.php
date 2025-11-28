<?php $pageTitle = 'Nueva Mesa'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/tables?restaurant_id=<?= $restaurant['id'] ?>" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Mesas
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Nueva Mesa</h2>
        <p class="text-gray-600"><?= htmlspecialchars($restaurant['name']) ?></p>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/tables/create?restaurant_id=<?= $restaurant['id'] ?>" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número/Nombre de Mesa *</label>
                <input type="text" name="table_number" required placeholder="Ej: M1, Mesa 1, VIP-1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Área</label>
                <select name="area_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Sin área específica</option>
                    <?php foreach ($areas as $area): ?>
                    <option value="<?= $area['id'] ?>"><?= htmlspecialchars($area['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad máxima *</label>
                <input type="number" name="capacity" required min="1" max="50" value="4"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad mínima</label>
                <input type="number" name="min_capacity" min="1" max="50" value="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Forma</label>
                <select name="shape"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="square">Cuadrada</option>
                    <option value="round">Redonda</option>
                    <option value="rectangular">Rectangular</option>
                    <option value="other">Otra</option>
                </select>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
            <textarea name="notes" rows="2" placeholder="Notas adicionales sobre la mesa..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
        </div>
        
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_combinable" value="1"
                       class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-gray-700">Mesa combinable con otras</span>
            </label>
        </div>
        
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="<?= BASE_URL ?>/admin/tables?restaurant_id=<?= $restaurant['id'] ?>" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Crear Mesa
            </button>
        </div>
    </form>
</div>
