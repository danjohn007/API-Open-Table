<?php $pageTitle = 'Completar Reservación'; ?>

<div class="max-w-2xl mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/reservar/buscar?restaurant_id=<?= $restaurant['id'] ?>&date=<?= $date ?>&party_size=<?= $partySize ?>" class="inline-flex items-center text-gray-600 hover:text-primary">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver a horarios
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Resumen de la reservación -->
        <div class="p-6 bg-gradient-to-r from-primary to-secondary text-white">
            <h1 class="text-2xl font-bold mb-4">Completar Reservación</h1>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-white/70">Restaurante</span>
                    <p class="font-semibold"><?= htmlspecialchars($restaurant['name']) ?></p>
                </div>
                <div>
                    <span class="text-white/70">Fecha</span>
                    <p class="font-semibold"><?= date('d/m/Y', strtotime($date)) ?></p>
                </div>
                <div>
                    <span class="text-white/70">Hora</span>
                    <p class="font-semibold"><?= $time ?></p>
                </div>
                <div>
                    <span class="text-white/70">Personas</span>
                    <p class="font-semibold"><?= $partySize ?> <?= $partySize == 1 ? 'persona' : 'personas' ?></p>
                </div>
            </div>
        </div>
        
        <!-- Formulario -->
        <form action="<?= BASE_URL ?>/reservar/procesar" method="POST" class="p-6 space-y-6">
            <input type="hidden" name="restaurant_id" value="<?= $restaurant['id'] ?>">
            <input type="hidden" name="date" value="<?= $date ?>">
            <input type="hidden" name="time" value="<?= $time ?>">
            <input type="hidden" name="party_size" value="<?= $partySize ?>">
            
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-4">Tus datos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" name="first_name" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                        <input type="text" name="last_name" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Información adicional</h3>
                
                <div class="space-y-4">
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
                        </select>
                    </div>
                    
                    <?php if (!empty($areas)): ?>
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
                    <?php endif; ?>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Solicitudes especiales</label>
                        <textarea name="special_requests" rows="3" 
                                  placeholder="Alergias, restricciones dietéticas, preferencias de mesa..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="border-t pt-6">
                <div class="flex items-start mb-4">
                    <input type="checkbox" id="terms" required 
                           class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                    <label for="terms" class="ml-2 text-sm text-gray-600">
                        Acepto los términos y condiciones y la política de cancelación del restaurante.
                    </label>
                </div>
                
                <button type="submit" class="w-full py-4 bg-primary text-white text-lg font-semibold rounded-lg hover:bg-secondary transition-colors">
                    Confirmar Reservación
                </button>
            </div>
        </form>
    </div>
</div>
