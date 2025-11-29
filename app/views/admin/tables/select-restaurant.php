<?php $pageTitle = 'Seleccionar Restaurante'; ?>

<div class="text-center mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Seleccionar Restaurante</h2>
    <p class="text-gray-600">Elige el restaurante para gestionar sus mesas</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
    <?php foreach ($restaurants as $restaurant): ?>
    <a href="<?= BASE_URL ?>/admin/tables?restaurant_id=<?= $restaurant['id'] ?>" 
       class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow block">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800"><?= htmlspecialchars($restaurant['name']) ?></h3>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($restaurant['city']) ?></p>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
</div>
