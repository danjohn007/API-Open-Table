<?php $pageTitle = 'Términos y Condiciones'; ?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Términos y Condiciones</h1>
    
    <?php if (!empty($terms)): ?>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Términos del Servicio</h2>
        <div class="prose prose-gray max-w-none">
            <?= nl2br(htmlspecialchars($terms)) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($privacy)): ?>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Política de Privacidad</h2>
        <div class="prose prose-gray max-w-none">
            <?= nl2br(htmlspecialchars($privacy)) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($cancellation)): ?>
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Política de Cancelación</h2>
        <div class="prose prose-gray max-w-none">
            <?= nl2br(htmlspecialchars($cancellation)) ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (empty($terms) && empty($privacy) && empty($cancellation)): ?>
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Políticas no configuradas</h3>
        <p class="text-gray-500">Los términos y condiciones aún no han sido configurados.</p>
    </div>
    <?php endif; ?>
    
    <div class="text-center mt-8">
        <a href="<?= BASE_URL ?>" class="text-primary hover:text-secondary">← Volver al inicio</a>
    </div>
</div>
