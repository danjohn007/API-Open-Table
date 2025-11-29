<?php $pageTitle = 'Mapa de Mesas'; ?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Mapa de Mesas</h2>
        <p class="text-gray-600"><?= htmlspecialchars($restaurant['name']) ?></p>
    </div>
    <div class="flex items-center gap-4">
        <input type="date" id="mapDate" value="<?= htmlspecialchars($date) ?>" 
               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary"
               onchange="window.location.href='<?= BASE_URL ?>/admin/tables/map?restaurant_id=<?= $restaurant['id'] ?>&date=' + this.value">
        <a href="<?= BASE_URL ?>/admin/tables?restaurant_id=<?= $restaurant['id'] ?>" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            Ver Lista
        </a>
    </div>
</div>

<!-- Leyenda -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <div class="flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-green-500 rounded"></div>
            <span class="text-sm text-gray-600">Disponible</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-blue-500 rounded"></div>
            <span class="text-sm text-gray-600">Reservada</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-purple-500 rounded"></div>
            <span class="text-sm text-gray-600">Ocupada</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-gray-400 rounded"></div>
            <span class="text-sm text-gray-600">Bloqueada</span>
        </div>
    </div>
</div>

<?php 
// Group tables by area
$tablesByArea = [];
$noAreaTables = [];
foreach ($occupancy as $table) {
    $areaName = $table['area_name'] ?? null;
    if ($areaName) {
        if (!isset($tablesByArea[$areaName])) {
            $tablesByArea[$areaName] = [];
        }
        $tablesByArea[$areaName][] = $table;
    } else {
        $noAreaTables[] = $table;
    }
}
?>

<!-- Mesas por área -->
<?php foreach ($areas as $area): ?>
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">
            <?= htmlspecialchars($area['name']) ?>
            <?php if ($area['is_outdoor']): ?>
                <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded">Exterior</span>
            <?php endif; ?>
            <?php if ($area['is_vip']): ?>
                <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded">VIP</span>
            <?php endif; ?>
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php 
            $areaTables = $tablesByArea[$area['name']] ?? [];
            // Remove duplicates by table_number
            $uniqueTables = [];
            foreach ($areaTables as $table) {
                if (!isset($uniqueTables[$table['table_number']])) {
                    $uniqueTables[$table['table_number']] = $table;
                } elseif (!empty($table['reservation_id'])) {
                    // Keep the one with reservation
                    $uniqueTables[$table['table_number']] = $table;
                }
            }
            
            foreach ($uniqueTables as $table): 
                $statusClass = 'bg-green-500';
                $statusText = 'Disponible';
                $borderClass = 'border-green-200 bg-green-50';
                
                if (!empty($table['reservation_id'])) {
                    if ($table['status'] === 'seated') {
                        $statusClass = 'bg-purple-500';
                        $statusText = 'Ocupada';
                        $borderClass = 'border-purple-200 bg-purple-50';
                    } else {
                        $statusClass = 'bg-blue-500';
                        $statusText = 'Reservada';
                        $borderClass = 'border-blue-200 bg-blue-50';
                    }
                }
            ?>
            <div class="relative p-4 rounded-xl border-2 <?= $borderClass ?> text-center cursor-pointer hover:shadow-md transition-shadow"
                 title="<?= $statusText ?>">
                <div class="absolute top-2 right-2 w-3 h-3 rounded-full <?= $statusClass ?>"></div>
                <div class="text-lg font-bold text-gray-800"><?= htmlspecialchars($table['table_number']) ?></div>
                <div class="text-sm text-gray-500"><?= $table['capacity'] ?> pers.</div>
                <?php if (!empty($table['reservation_time'])): ?>
                <div class="text-xs text-gray-400 mt-1"><?= substr($table['reservation_time'], 0, 5) ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($areaTables)): ?>
        <p class="text-gray-500 text-center py-4">No hay mesas en esta área</p>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<!-- Mesas sin área -->
<?php if (!empty($noAreaTables)): ?>
<?php 
$uniqueNoArea = [];
foreach ($noAreaTables as $table) {
    if (!isset($uniqueNoArea[$table['table_number']])) {
        $uniqueNoArea[$table['table_number']] = $table;
    } elseif (!empty($table['reservation_id'])) {
        $uniqueNoArea[$table['table_number']] = $table;
    }
}
?>
<div class="bg-white rounded-xl shadow-sm mb-6">
    <div class="p-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Sin Área Asignada</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <?php foreach ($uniqueNoArea as $table): 
                $statusClass = 'bg-green-500';
                $borderClass = 'border-gray-200 bg-gray-50';
                
                if (!empty($table['reservation_id'])) {
                    if ($table['status'] === 'seated') {
                        $statusClass = 'bg-purple-500';
                    } else {
                        $statusClass = 'bg-blue-500';
                    }
                }
            ?>
            <div class="relative p-4 rounded-xl border-2 <?= $borderClass ?> text-center cursor-pointer hover:shadow-md transition-shadow">
                <div class="absolute top-2 right-2 w-3 h-3 rounded-full <?= $statusClass ?>"></div>
                <div class="text-lg font-bold text-gray-800"><?= htmlspecialchars($table['table_number']) ?></div>
                <div class="text-sm text-gray-500"><?= $table['capacity'] ?> pers.</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
