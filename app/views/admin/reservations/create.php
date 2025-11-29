<?php $pageTitle = 'Nueva Reservación'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/reservations" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Reservaciones
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Nueva Reservación</h2>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/reservations/create" class="p-6 space-y-6" x-data="reservationForm()">
        <!-- Selección de restaurante -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Restaurante *</label>
                <select name="restaurant_id" required x-model="restaurantId" @change="loadTables()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="">Seleccionar restaurante</option>
                    <?php foreach ($restaurants as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= ($restaurant && $restaurant['id'] == $r['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Fecha y hora -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Fecha y Hora</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                    <input type="date" name="reservation_date" required x-model="date" 
                           min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                    <input type="time" name="reservation_time" required x-model="time"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personas *</label>
                    <input type="number" name="party_size" required min="1" max="20" value="2" x-model="partySize"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duración (min)</label>
                    <input type="number" name="duration_minutes" min="30" max="240" value="90"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
        
        <!-- Datos del cliente -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Datos del Cliente</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="customer_first_name" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                    <input type="text" name="customer_last_name" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="customer_email" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono/WhatsApp *</label>
                    <input type="tel" name="customer_phone" required pattern="[0-9]{10}" maxlength="10"
                           placeholder="10 dígitos"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)">
                    <p class="text-xs text-gray-500 mt-1">Ingrese exactamente 10 dígitos</p>
                </div>
            </div>
        </div>
        
        <!-- Mesa y área -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Mesa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mesa (opcional)</label>
                    <select name="table_id" x-model="tableId"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="">Asignar después</option>
                        <?php foreach ($tables as $table): ?>
                        <option value="<?= $table['id'] ?>">
                            <?= htmlspecialchars($table['table_number']) ?> 
                            (<?= $table['min_capacity'] ?>-<?= $table['capacity'] ?> pers.)
                            <?php if ($table['area_name']): ?>- <?= htmlspecialchars($table['area_name']) ?><?php endif; ?>
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
                        <option value="<?= htmlspecialchars($area['name']) ?>"><?= htmlspecialchars($area['name']) ?></option>
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
                        <option value="Cumpleaños">Cumpleaños</option>
                        <option value="Aniversario">Aniversario</option>
                        <option value="Cena romántica">Cena romántica</option>
                        <option value="Reunión de negocios">Reunión de negocios</option>
                        <option value="Celebración">Celebración</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="confirmed">Confirmada</option>
                        <option value="pending">Pendiente</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Solicitudes especiales</label>
                <textarea name="special_requests" rows="3" 
                          placeholder="Alergias, restricciones dietéticas, solicitudes especiales..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas</label>
                <textarea name="internal_notes" rows="2" 
                          placeholder="Notas visibles solo para el personal..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
            </div>
        </div>
        
        <!-- Botones -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="<?= BASE_URL ?>/admin/reservations" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Crear Reservación
            </button>
        </div>
    </form>
</div>

<script>
function reservationForm() {
    return {
        restaurantId: '<?= $restaurant ? $restaurant['id'] : '' ?>',
        date: '<?= date('Y-m-d') ?>',
        time: '19:00',
        partySize: 2,
        tableId: '',
        
        async loadTables() {
            if (!this.restaurantId) return;
            
            try {
                const response = await fetch(BASE_URL + '/admin/tables/availability?restaurant_id=' + this.restaurantId + '&date=' + this.date + '&time=' + this.time + '&party_size=' + this.partySize);
                const data = await response.json();
                
                // Actualizar opciones de mesa si es necesario
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }
}
</script>
