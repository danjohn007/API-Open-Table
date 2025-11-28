<?php $pageTitle = 'Configuración General'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Configuraciones generales del sistema</p>
    </div>
</div>

<!-- Tabs de navegación -->
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px overflow-x-auto">
            <a href="<?= BASE_URL ?>/admin/settings" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">General</a>
            <a href="<?= BASE_URL ?>/admin/settings/appearance" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Apariencia</a>
            <a href="<?= BASE_URL ?>/admin/settings/mail" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Correo</a>
            <a href="<?= BASE_URL ?>/admin/settings/payment" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Pagos</a>
            <a href="<?= BASE_URL ?>/admin/settings/opentable" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">OpenTable</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Usuarios</a>
        </nav>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/settings" enctype="multipart/form-data" class="p-6 space-y-6">
        <!-- Nombre del sitio -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Sitio</label>
            <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <!-- Logo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Logo del Sitio</label>
            <?php if (!empty($settings['site_logo'])): ?>
            <div class="mb-2">
                <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($settings['site_logo']) ?>" alt="Logo" class="h-16">
            </div>
            <?php endif; ?>
            <input type="file" name="site_logo" accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <!-- Información de contacto -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Información de Contacto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email de Contacto</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono de Contacto</label>
                    <input type="tel" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Horario de Atención</label>
                    <input type="text" name="support_hours" value="<?= htmlspecialchars($settings['support_hours'] ?? '') ?>"
                           placeholder="Ej: Lunes a Viernes 9:00 - 18:00"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
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
