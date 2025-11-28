<?php $pageTitle = 'Inicio'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary to-secondary py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Reserva tu mesa favorita
        </h1>
        <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
            Encuentra los mejores restaurantes de Querétaro y reserva en segundos
        </p>
        <a href="<?= BASE_URL ?>/reservar" class="inline-flex items-center px-8 py-4 bg-white text-primary font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Hacer Reservación
        </a>
    </div>
</section>

<!-- Restaurantes destacados -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Restaurantes Destacados</h2>
            <p class="text-gray-600">Descubre los mejores restaurantes en Querétaro</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($restaurants as $restaurant): ?>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                <div class="h-48 bg-gradient-to-br from-primary/20 to-secondary/20 relative">
                    <?php if ($restaurant['cover_image']): ?>
                    <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($restaurant['cover_image']) ?>" 
                         alt="<?= htmlspecialchars($restaurant['name']) ?>" 
                         class="w-full h-full object-cover">
                    <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($restaurant['name']) ?></h3>
                    <p class="text-gray-600 text-sm mb-3"><?= htmlspecialchars($restaurant['city']) ?>, <?= htmlspecialchars($restaurant['state']) ?></p>
                    
                    <?php if ($restaurant['description']): ?>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2"><?= htmlspecialchars(substr($restaurant['description'], 0, 100)) ?>...</p>
                    <?php endif; ?>
                    
                    <div class="flex items-center text-sm text-gray-500 mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <?= date('H:i', strtotime($restaurant['opening_time'])) ?> - <?= date('H:i', strtotime($restaurant['closing_time'])) ?>
                    </div>
                    
                    <a href="<?= BASE_URL ?>/reservar?restaurant_id=<?= $restaurant['id'] ?>" 
                       class="block w-full text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                        Reservar Ahora
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($restaurants)): ?>
        <div class="text-center py-12">
            <p class="text-gray-500">No hay restaurantes disponibles en este momento.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Cómo funciona -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">¿Cómo Funciona?</h2>
            <p class="text-gray-600">Reservar es muy fácil, solo sigue estos pasos</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-primary">1</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Elige tu Restaurante</h3>
                <p class="text-gray-600">Explora nuestra selección de restaurantes y encuentra el perfecto para ti.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-primary">2</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Selecciona Fecha y Hora</h3>
                <p class="text-gray-600">Elige el día y horario que mejor se adapte a tus planes.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-primary">3</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">¡Listo!</h3>
                <p class="text-gray-600">Recibe tu confirmación y disfruta de tu experiencia gastronómica.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿Ya tienes una reservación?</h2>
        <p class="text-white/80 mb-8">Consulta, modifica o cancela tu reservación en cualquier momento.</p>
        <a href="<?= BASE_URL ?>/reservar/consultar" class="inline-flex items-center px-8 py-3 bg-white text-primary font-semibold rounded-lg hover:bg-gray-100 transition-colors">
            Consultar Reservación
        </a>
    </div>
</section>
