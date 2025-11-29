<?php $pageTitle = 'Políticas del Sitio'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Políticas y términos del sitio</p>
    </div>
</div>

<!-- Tabs de navegación -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto">
            <a href="<?= BASE_URL ?>/admin/settings" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">General</a>
            <a href="<?= BASE_URL ?>/admin/settings/appearance" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Apariencia</a>
            <a href="<?= BASE_URL ?>/admin/settings/mail" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Correo</a>
            <a href="<?= BASE_URL ?>/admin/settings/payment" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Pagos</a>
            <a href="<?= BASE_URL ?>/admin/settings/opentable" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">OpenTable</a>
            <a href="<?= BASE_URL ?>/admin/settings/policies" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">Políticas del Sitio</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Usuarios</a>
        </nav>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings/policies" class="p-6 space-y-6">
        <!-- Términos y Condiciones -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Términos y Condiciones</label>
            <textarea name="terms_and_conditions" rows="10" 
                      placeholder="Ingresa los términos y condiciones del servicio..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($settings['terms_and_conditions'] ?? '') ?></textarea>
            <p class="text-xs text-gray-500 mt-1">Estos términos se mostrarán a los usuarios durante el registro.</p>
        </div>
        
        <!-- Política de Privacidad -->
        <div class="border-t pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Política de Privacidad</label>
            <textarea name="privacy_policy" rows="10" 
                      placeholder="Ingresa la política de privacidad..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($settings['privacy_policy'] ?? '') ?></textarea>
        </div>
        
        <!-- Política de Cancelación -->
        <div class="border-t pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Política de Cancelación</label>
            <textarea name="cancellation_policy" rows="6" 
                      placeholder="Ingresa la política de cancelación de reservaciones..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($settings['cancellation_policy'] ?? '') ?></textarea>
        </div>
        
        <!-- Política de No Show -->
        <div class="border-t pt-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Política de No Show</label>
            <textarea name="no_show_policy" rows="4" 
                      placeholder="Ingresa la política para cuando un cliente no se presenta..."
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"><?= htmlspecialchars($settings['no_show_policy'] ?? '') ?></textarea>
        </div>
        
        <div class="flex justify-end pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
