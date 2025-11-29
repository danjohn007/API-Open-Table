<?php $pageTitle = 'Usuarios'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Configuración</h2>
        <p class="text-gray-600">Gestión de usuarios del sistema</p>
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
            <a href="<?= BASE_URL ?>/admin/settings/policies" class="px-6 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-700">Políticas del Sitio</a>
            <a href="<?= BASE_URL ?>/admin/settings/users" class="px-6 py-3 border-b-2 border-primary text-primary font-medium">Usuarios</a>
        </nav>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Lista de usuarios -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Usuarios del Sistema</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-3">Usuario</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Rol</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-medium mr-3">
                                        <?= strtoupper(substr($user['first_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></p>
                                        <p class="text-xs text-gray-500">@<?= htmlspecialchars($user['username']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php 
                                    switch($user['role']) {
                                        case 'admin': echo 'bg-purple-100 text-purple-700'; break;
                                        case 'manager': echo 'bg-blue-100 text-blue-700'; break;
                                        case 'staff': echo 'bg-green-100 text-green-700'; break;
                                        default: echo 'bg-gray-100 text-gray-700';
                                    }
                                    ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full <?= $user['is_active'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $user['is_active'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="<?= BASE_URL ?>/admin/settings/users" class="inline">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="text-gray-400 hover:text-primary" title="<?= $user['is_active'] ? 'Desactivar' : 'Activar' ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Formulario de nuevo usuario -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Nuevo Usuario</h3>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/settings/users" class="p-6 space-y-4">
                <input type="hidden" name="action" value="create">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="first_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" name="last_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="tel" name="phone" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password" required minlength="6"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="role" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                        <option value="staff">Staff</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                
                <button type="submit" class="w-full py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                    Crear Usuario
                </button>
            </form>
        </div>
    </div>
</div>
