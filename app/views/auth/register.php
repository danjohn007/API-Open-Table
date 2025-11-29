<?php $pageTitle = 'Registro'; ?>

<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="text-center mb-8">
        <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        <h1 class="text-2xl font-bold text-gray-800">Crear Cuenta</h1>
        <p class="text-gray-600 mt-2">Regístrate para hacer reservaciones</p>
    </div>
    
    <?php if (isset($error) && $error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success): ?>
    <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
        <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= BASE_URL ?>/register" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="first_name" id="first_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                <input type="text" name="last_name" id="last_name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            </div>
        </div>
        
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nombre de usuario</label>
            <input type="text" name="username" id="username" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
            <input type="email" name="email" id="email" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="tel" name="phone" id="phone"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required minlength="6"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
        </div>
        
        <button type="submit" class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition-colors">
            Crear Cuenta
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-gray-600">
            ¿Ya tienes cuenta? 
            <a href="<?= BASE_URL ?>/login" class="text-primary hover:text-secondary font-medium">Inicia sesión</a>
        </p>
    </div>
</div>
