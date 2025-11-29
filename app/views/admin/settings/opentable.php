<?php $pageTitle = 'Configuración de OpenTable'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Configuración de integración con OpenTable</p>
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
            <a href="<?= BASE_URL ?>/admin/settings/opentable" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">OpenTable</a>
            <a href="<?= BASE_URL ?>/admin/settings/policies" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Políticas del Sitio</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Usuarios</a>
        </nav>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings/opentable" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">API Key</label>
                <input type="text" name="opentable_api_key" value="<?= htmlspecialchars($settings['opentable_api_key'] ?? '') ?>"
                       placeholder="Tu API Key de OpenTable"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Restaurant ID</label>
                <input type="text" name="opentable_restaurant_id" value="<?= htmlspecialchars($settings['opentable_restaurant_id'] ?? '') ?>"
                       placeholder="ID del restaurante en OpenTable"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ambiente</label>
                <select name="opentable_environment"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="sandbox" <?= ($settings['opentable_environment'] ?? '') === 'sandbox' ? 'selected' : '' ?>>Sandbox (Pruebas)</option>
                    <option value="production" <?= ($settings['opentable_environment'] ?? '') === 'production' ? 'selected' : '' ?>>Producción</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sincronización Automática</label>
                <select name="opentable_auto_sync"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    <option value="0" <?= ($settings['opentable_auto_sync'] ?? '0') === '0' ? 'selected' : '' ?>>Desactivada</option>
                    <option value="1" <?= ($settings['opentable_auto_sync'] ?? '0') === '1' ? 'selected' : '' ?>>Activada</option>
                </select>
            </div>
        </div>
        
        <!-- Test de conexión -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Probar Conexión</h3>
            <div class="flex items-center gap-4">
                <button type="button" onclick="testOpentable()" 
                        class="px-6 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Probar Conexión con OpenTable
                </button>
                <span id="connectionStatus" class="text-sm text-gray-500"></span>
            </div>
        </div>
        
        <div class="flex justify-end pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
async function testOpentable() {
    const statusEl = document.getElementById('connectionStatus');
    statusEl.textContent = 'Probando conexión...';
    statusEl.className = 'text-sm text-gray-500';
    
    try {
        const response = await fetch(BASE_URL + '/admin/settings/test-opentable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        
        if (data.success) {
            statusEl.textContent = data.message;
            statusEl.className = 'text-sm text-green-600';
        } else {
            statusEl.textContent = data.error || 'Error de conexión';
            statusEl.className = 'text-sm text-red-600';
        }
    } catch (error) {
        statusEl.textContent = 'Error al probar conexión';
        statusEl.className = 'text-sm text-red-600';
    }
}
</script>
