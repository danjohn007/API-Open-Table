<?php $pageTitle = 'Editar Cliente'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver al Cliente
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Editar Cliente</h2>
        <p class="text-gray-600"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></p>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>/edit" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                <input type="text" name="first_name" required value="<?= htmlspecialchars($customer['first_name']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Apellido *</label>
                <input type="text" name="last_name" required value="<?= htmlspecialchars($customer['last_name']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico *</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($customer['email']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono/WhatsApp *</label>
                <input type="tel" name="phone" required value="<?= htmlspecialchars($customer['phone']) ?>"
                       pattern="[0-9]{10}" maxlength="10" placeholder="10 dígitos"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)">
                <p class="text-xs text-gray-500 mt-1">Ingrese exactamente 10 dígitos</p>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
            <textarea name="notes" rows="3" 
                      placeholder="Preferencias, alergias, información importante..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($customer['notes'] ?? '') ?></textarea>
        </div>
        
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="vip_status" value="1" <?= $customer['vip_status'] ? 'checked' : '' ?>
                       class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-gray-700">Cliente VIP</span>
            </label>
        </div>
        
        <!-- Estadísticas (solo lectura) -->
        <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Estadísticas</h3>
            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?= $customer['total_visits'] ?></p>
                    <p class="text-xs text-gray-500">Visitas</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900"><?= $customer['total_cancellations'] ?></p>
                    <p class="text-xs text-gray-500">Cancelaciones</p>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600"><?= $customer['total_no_shows'] ?></p>
                    <p class="text-xs text-gray-500">No Shows</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="<?= BASE_URL ?>/admin/customers/<?= $customer['id'] ?>" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
