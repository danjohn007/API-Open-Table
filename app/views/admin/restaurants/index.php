<?php $pageTitle = 'Restaurantes'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Restaurantes</h2>
        <p class="text-gray-600">Gestiona los restaurantes del sistema</p>
    </div>
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
    <a href="<?= BASE_URL ?>/admin/restaurants/create" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nuevo Restaurante
    </a>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($restaurants as $restaurant): ?>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
            <?php if ($restaurant['cover_image']): ?>
            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($restaurant['cover_image']) ?>" 
                 alt="<?= htmlspecialchars($restaurant['name']) ?>" 
                 class="w-full h-48 object-cover">
            <?php else: ?>
            <div class="w-full h-48 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($restaurant['name']) ?></h3>
                    <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($restaurant['city']) ?>, <?= htmlspecialchars($restaurant['state']) ?></p>
                </div>
                <span class="px-2 py-1 text-xs rounded-full <?= $restaurant['is_active'] ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
                    <?= $restaurant['is_active'] ? 'Activo' : 'Inactivo' ?>
                </span>
            </div>
            
            <div class="mt-4 flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <?= date('H:i', strtotime($restaurant['opening_time'])) ?> - <?= date('H:i', strtotime($restaurant['closing_time'])) ?>
            </div>
            
            <div class="mt-2 flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <?= htmlspecialchars($restaurant['phone']) ?>
            </div>
            
            <div class="mt-6 flex space-x-2">
                <a href="<?= BASE_URL ?>/admin/restaurants/<?= $restaurant['id'] ?>" 
                   class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                    Ver Detalles
                </a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/restaurants/<?= $restaurant['id'] ?>/edit" 
                   class="px-3 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($restaurants)): ?>
<div class="bg-white rounded-xl shadow-sm p-12 text-center">
    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
    </svg>
    <h3 class="text-lg font-medium text-gray-900">No hay restaurantes</h3>
    <p class="mt-2 text-gray-500">Comienza agregando tu primer restaurante.</p>
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
    <a href="<?= BASE_URL ?>/admin/restaurants/create" class="mt-4 inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Crear Restaurante
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>
