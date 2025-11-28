<?php $pageTitle = 'Recuperar Contraseña'; ?>

<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="text-center mb-8">
        <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
        </svg>
        <h1 class="text-2xl font-bold text-gray-800">Recuperar Contraseña</h1>
        <p class="text-gray-600 mt-2">Te enviaremos instrucciones por correo</p>
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
    
    <form method="POST" action="<?= BASE_URL ?>/forgot-password" class="space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
            <input type="email" name="email" id="email" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                   placeholder="Tu correo electrónico registrado">
        </div>
        
        <button type="submit" class="w-full py-3 px-4 bg-primary text-white font-medium rounded-lg hover:bg-secondary transition-colors">
            Enviar Instrucciones
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="<?= BASE_URL ?>/login" class="text-primary hover:text-secondary font-medium">
            ← Volver al inicio de sesión
        </a>
    </div>
</div>
