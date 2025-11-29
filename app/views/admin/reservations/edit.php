<?php $pageTitle = 'Editar Reservación'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a la Reservación
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Editar Reservación</h2>
                <p class="text-gray-600">Código: <?= htmlspecialchars($reservation['confirmation_code']) ?></p>
            </div>
            <span class="px-3 py-1 text-sm rounded-full 
                <?php 
                switch($reservation['status']) {
                    case 'pending': echo 'bg-yellow-100 text-yellow-700'; break;
                    case 'confirmed': echo 'bg-green-100 text-green-700'; break;
                    case 'cancelled': echo 'bg-red-100 text-red-700'; break;
                    default: echo 'bg-gray-100 text-gray-700';
                }
                ?>">
                <?= ucfirst($reservation['status']) ?>
            </span>
        </div>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>/edit" class="p-6 space-y-6">
        <!-- Cliente (solo lectura) -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Cliente</h3>
            <p class="text-gray-900"><?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?></p>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($reservation['customer_email']) ?> | <?= htmlspecialchars($reservation['customer_phone']) ?></p>
        </div>
        
        <!-- Fecha y hora -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Fecha y Hora</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                    <input type="date" name="reservation_date" required 
                           value="<?= htmlspecialchars($reservation['reservation_date']) ?>"
                           min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                    <input type="time" name="reservation_time" required 
                           value="<?= htmlspecialchars(substr($reservation['reservation_time'], 0, 5)) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personas *</label>
                    <input type="number" name="party_size" required min="1" max="20" 
                           value="<?= htmlspecialchars($reservation['party_size']) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duración (min)</label>
                    <input type="number" name="duration_minutes" min="30" max="240" 
                           value="<?= htmlspecialchars($reservation['duration_minutes']) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
        
        <!-- Mesa y área -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Mesa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mesa</label>
                    <select name="table_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Sin asignar</option>
                        <?php foreach ($tables as $table): ?>
                        <option value="<?= $table['id'] ?>" <?= $reservation['table_id'] == $table['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($table['table_number']) ?> 
                            (<?= $table['min_capacity'] ?>-<?= $table['capacity'] ?> pers.)
                            <?php if (!empty($table['area_name'])): ?>- <?= htmlspecialchars($table['area_name']) ?><?php endif; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferencia de área</label>
                    <select name="area_preference"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Sin preferencia</option>
                        <?php foreach ($areas as $area): ?>
                        <option value="<?= htmlspecialchars($area['name']) ?>" 
                                <?= $reservation['area_preference'] == $area['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($area['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Información adicional -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Información Adicional</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ocasión</label>
                    <select name="occasion"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Ninguna</option>
                        <option value="Cumpleaños" <?= $reservation['occasion'] == 'Cumpleaños' ? 'selected' : '' ?>>Cumpleaños</option>
                        <option value="Aniversario" <?= $reservation['occasion'] == 'Aniversario' ? 'selected' : '' ?>>Aniversario</option>
                        <option value="Cena romántica" <?= $reservation['occasion'] == 'Cena romántica' ? 'selected' : '' ?>>Cena romántica</option>
                        <option value="Reunión de negocios" <?= $reservation['occasion'] == 'Reunión de negocios' ? 'selected' : '' ?>>Reunión de negocios</option>
                        <option value="Celebración" <?= $reservation['occasion'] == 'Celebración' ? 'selected' : '' ?>>Celebración</option>
                        <option value="Otro" <?= $reservation['occasion'] == 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Solicitudes especiales</label>
                <textarea name="special_requests" rows="3" 
                          placeholder="Alergias, restricciones dietéticas, solicitudes especiales..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($reservation['special_requests'] ?? '') ?></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas</label>
                <textarea name="internal_notes" rows="2" 
                          placeholder="Notas visibles solo para el personal..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($reservation['internal_notes'] ?? '') ?></textarea>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
