<?php $pageTitle = 'Apariencia'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Personaliza la apariencia del sistema</p>
    </div>
</div>

<!-- Tabs de navegación -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto">
            <a href="<?= BASE_URL ?>/admin/settings" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">General</a>
            <a href="<?= BASE_URL ?>/admin/settings/appearance" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">Apariencia</a>
            <a href="<?= BASE_URL ?>/admin/settings/mail" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Correo</a>
            <a href="<?= BASE_URL ?>/admin/settings/payment" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Pagos</a>
            <a href="<?= BASE_URL ?>/admin/settings/opentable" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">OpenTable</a>
            <a href="<?= BASE_URL ?>/admin/settings/policies" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Políticas del Sitio</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Usuarios</a>
        </nav>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings/appearance" class="p-6 space-y-6">
        <!-- Colores -->
        <div>
            <h3 class="text-lg font-medium text-gray-800 mb-4">Colores del Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color Primario</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="primary_color" value="<?= htmlspecialchars($settings['primary_color'] ?? '#2563eb') ?>"
                               class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                        <input type="text" value="<?= htmlspecialchars($settings['primary_color'] ?? '#2563eb') ?>"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color Secundario</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="secondary_color" value="<?= htmlspecialchars($settings['secondary_color'] ?? '#1e40af') ?>"
                               class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                        <input type="text" value="<?= htmlspecialchars($settings['secondary_color'] ?? '#1e40af') ?>"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color de Acento</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" name="accent_color" value="<?= htmlspecialchars($settings['accent_color'] ?? '#3b82f6') ?>"
                               class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                        <input type="text" value="<?= htmlspecialchars($settings['accent_color'] ?? '#3b82f6') ?>"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Vista previa -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Vista Previa</h3>
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex space-x-4">
                    <button class="px-4 py-2 bg-primary text-white rounded-lg">Botón Primario</button>
                    <button class="px-4 py-2 bg-secondary text-white rounded-lg">Botón Secundario</button>
                    <button class="px-4 py-2 bg-accent text-white rounded-lg">Botón Acento</button>
                </div>
            </div>
        </div>
        
        <div class="flex justify-end pt-6 border-t">
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
