<?php $pageTitle = 'Nuevo Cliente'; ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/customers" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Clientes
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm max-w-2xl">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Nuevo Cliente</h2>
        <p class="text-gray-600">Registrar un nuevo cliente en el sistema</p>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>/admin/customers/create" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono/WhatsApp *</label>
                <input type="tel" name="phone" required pattern="[0-9]{10}" maxlength="10"
                       placeholder="10 dígitos"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)">
                <p class="text-xs text-gray-500 mt-1">Ingrese exactamente 10 dígitos</p>
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
            <textarea name="notes" rows="3" 
                      placeholder="Preferencias, alergias, información importante..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"></textarea>
        </div>
        
        <div>
            <label class="flex items-center">
                <input type="checkbox" name="vip_status" value="1"
                       class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-gray-700">Cliente VIP</span>
            </label>
        </div>
        
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <a href="<?= BASE_URL ?>/admin/customers" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Crear Cliente
            </button>
        </div>
    </form>
</div>
