<?php $pageTitle = 'Mi Perfil'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Mi Perfil</h2>
        <p class="text-gray-600">Administra tu información personal</p>
    </div>
</div>

<?php if (!empty($error)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<?php if (!empty($success)): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
    <?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm">
    <form method="POST" action="<?= BASE_URL ?>/admin/profile" class="p-6 space-y-6">
        <!-- Información personal -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Información Personal</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
        
        <!-- Cambiar contraseña -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Cambiar Contraseña</h3>
            <p class="text-sm text-gray-500 mb-4">Deja en blanco si no deseas cambiar la contraseña</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <input type="password" name="new_password" minlength="6"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
            </div>
        </div>
        
        <!-- Información de cuenta -->
        <div class="pb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Información de Cuenta</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de Usuario</label>
                    <input type="text" value="<?= htmlspecialchars($user['username'] ?? '') ?>" disabled
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <input type="text" value="<?= htmlspecialchars(ucfirst($user['role'] ?? '')) ?>" disabled
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500">
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
