<?php $pageTitle = 'Modificar Reservación'; ?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Modificar Reservación</h1>
        <p class="text-gray-600">Actualiza los detalles de tu reservación</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Información actual -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Reservación Actual</h2>
                <span class="px-3 py-1 bg-primary text-white text-sm rounded-full font-mono">
                    <?= htmlspecialchars($reservation['confirmation_code']) ?>
                </span>
            </div>
            <div class="text-sm text-gray-600 space-y-1">
                <p><strong>Restaurante:</strong> <?= htmlspecialchars($restaurant['name']) ?></p>
                <p><strong>Fecha:</strong> <?= date('l, d F Y', strtotime($reservation['reservation_date'])) ?></p>
                <p><strong>Hora:</strong> <?= date('H:i', strtotime($reservation['reservation_time'])) ?> hrs</p>
                <p><strong>Personas:</strong> <?= $reservation['party_size'] ?></p>
            </div>
        </div>
        
        <!-- Formulario de modificación -->
        <form action="<?= BASE_URL ?>/reservar/modificar/<?= htmlspecialchars($reservation['confirmation_code']) ?>" method="POST" class="p-6">
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nueva fecha -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Fecha</label>
                        <input type="date" name="date" required 
                               min="<?= date('Y-m-d') ?>" 
                               max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                               value="<?= htmlspecialchars($reservation['reservation_date']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    
                    <!-- Nueva hora -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Hora</label>
                        <select name="time" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                            <?php 
                            $currentTime = date('H:i', strtotime($reservation['reservation_time']));
                            for ($h = 12; $h <= 22; $h++): 
                            ?>
                                <?php for ($m = 0; $m < 60; $m += 30): ?>
                                    <?php $time = sprintf('%02d:%02d', $h, $m); ?>
                                    <option value="<?= $time ?>" <?= $time === $currentTime ? 'selected' : '' ?>><?= $time ?></option>
                                <?php endfor; ?>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Número de personas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de Personas</label>
                    <select name="party_size" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <?php for ($i = 1; $i <= 20; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $reservation['party_size'] ? 'selected' : '' ?>>
                            <?= $i ?> <?= $i === 1 ? 'persona' : 'personas' ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <!-- Solicitudes especiales -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Solicitudes Especiales</label>
                    <textarea name="special_requests" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                              placeholder="Alergias, celebraciones, preferencias de mesa..."><?= htmlspecialchars($reservation['special_requests'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-secondary transition-colors">
                    Guardar Cambios
                </button>
                <a href="<?= BASE_URL ?>/reservar/consultar?code=<?= htmlspecialchars($reservation['confirmation_code']) ?>" 
                   class="flex-1 text-center py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
    
    <!-- Nota informativa -->
    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <p class="text-sm text-blue-700">
            <strong>Nota:</strong> La modificación está sujeta a disponibilidad. 
            Si la nueva fecha u hora no está disponible, serás notificado.
        </p>
    </div>
</div>
