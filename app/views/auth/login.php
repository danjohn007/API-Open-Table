<?php $pageTitle = 'Iniciar Sesión'; ?>

<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="text-center mb-8">
        <?php if (!empty($siteLogo)): ?>
        <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($siteLogo) ?>" alt="Logo" class="h-16 mx-auto mb-4">
        <?php else: ?>
        <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <?php endif; ?>
        <h1 class="text-2xl font-bold text-gray-800">Sistema de Reservaciones</h1>
        <p class="text-gray-600 mt-2">Ingresa a tu cuenta</p>
    </div>
    
    <?php if (isset($error) && $error): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
        <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>
    
    <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-6">
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Usuario o Email</label>
            <input type="text" name="username" id="username" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                   placeholder="Tu usuario o correo electrónico">
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input type="password" name="password" id="password" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                   placeholder="Tu contraseña">
        </div>
        
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary focus:ring-primary">
                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
            </label>
            <a href="<?= BASE_URL ?>/forgot-password" class="text-sm text-primary hover:text-secondary">¿Olvidaste tu contraseña?</a>
        </div>
        
        <button type="submit" class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition-colors">
            Iniciar Sesión
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <p class="text-gray-600">
            ¿No tienes cuenta? 
            <a href="<?= BASE_URL ?>/register" class="text-primary hover:text-secondary font-medium">Regístrate</a>
        </p>
    </div>
    
    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
        <a href="<?= BASE_URL ?>" class="text-gray-500 hover:text-gray-700 text-sm">
            ← Volver al inicio
        </a>
    </div>
</div>
