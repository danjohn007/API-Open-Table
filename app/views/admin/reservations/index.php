<?php $pageTitle = 'Reservaciones'; ?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Reservaciones</h2>
        <p class="text-gray-600">Gestiona las reservaciones de tus restaurantes</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/reservations/create" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nueva Reservación
    </a>
</div>

<!-- Filtros -->
<div class="bg-white rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Restaurante</label>
            <select name="restaurant_id" onchange="this.form.submit()" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <?php foreach ($restaurants as $r): ?>
                <option value="<?= $r['id'] ?>" <?= ($selectedRestaurant && $selectedRestaurant['id'] == $r['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="w-40">
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
            <input type="date" name="date" value="<?= $selectedDate ?>" onchange="this.form.submit()"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
        </div>
        
        <div class="w-40">
            <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
            <select name="status" onchange="this.form.submit()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="">Todos</option>
                <option value="pending" <?= $selectedStatus === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                <option value="confirmed" <?= $selectedStatus === 'confirmed' ? 'selected' : '' ?>>Confirmada</option>
                <option value="waiting" <?= $selectedStatus === 'waiting' ? 'selected' : '' ?>>En espera</option>
                <option value="seated" <?= $selectedStatus === 'seated' ? 'selected' : '' ?>>Sentado</option>
                <option value="completed" <?= $selectedStatus === 'completed' ? 'selected' : '' ?>>Completada</option>
                <option value="cancelled" <?= $selectedStatus === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                <option value="no_show" <?= $selectedStatus === 'no_show' ? 'selected' : '' ?>>No show</option>
            </select>
        </div>
        
        <a href="<?= BASE_URL ?>/admin/reservations/calendar<?= $selectedRestaurant ? '?restaurant_id=' . $selectedRestaurant['id'] : '' ?>" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </a>
    </form>
</div>

<!-- Lista de reservaciones -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <?php if (empty($reservations)): ?>
    <div class="p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900">No hay reservaciones</h3>
        <p class="mt-2 text-gray-500">No se encontraron reservaciones para los filtros seleccionados.</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-3">Hora</th>
                    <th class="px-6 py-3">Cliente</th>
                    <th class="px-6 py-3">Personas</th>
                    <th class="px-6 py-3">Mesa</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Código</th>
                    <th class="px-6 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($reservations as $reservation): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="font-medium text-gray-900"><?= date('H:i', strtotime($reservation['reservation_time'])) ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">
                                <?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?>
                            </p>
                            <p class="text-sm text-gray-500"><?= htmlspecialchars($reservation['customer_phone']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?= $reservation['party_size'] ?> personas
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php if ($reservation['table_number']): ?>
                            Mesa <?= htmlspecialchars($reservation['table_number']) ?>
                            <?php if ($reservation['area_name']): ?>
                                <span class="text-xs text-gray-400">(<?= htmlspecialchars($reservation['area_name']) ?>)</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-gray-400">Sin asignar</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <select onchange="changeStatus(<?= $reservation['id'] ?>, this.value)" 
                                class="px-2 py-1 text-sm rounded-full border-0 
                                <?php 
                                switch($reservation['status']) {
                                    case 'confirmed': echo 'bg-green-100 text-green-700'; break;
                                    case 'pending': echo 'bg-yellow-100 text-yellow-700'; break;
                                    case 'seated': echo 'bg-purple-100 text-purple-700'; break;
                                    case 'waiting': echo 'bg-blue-100 text-blue-700'; break;
                                    case 'completed': echo 'bg-gray-100 text-gray-700'; break;
                                    case 'cancelled': echo 'bg-red-100 text-red-700'; break;
                                    case 'no_show': echo 'bg-orange-100 text-orange-700'; break;
                                    default: echo 'bg-gray-100 text-gray-700';
                                }
                                ?>">
                            <option value="pending" <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmada</option>
                            <option value="waiting" <?= $reservation['status'] === 'waiting' ? 'selected' : '' ?>>En espera</option>
                            <option value="seated" <?= $reservation['status'] === 'seated' ? 'selected' : '' ?>>Sentado</option>
                            <option value="completed" <?= $reservation['status'] === 'completed' ? 'selected' : '' ?>>Completada</option>
                            <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                            <option value="no_show" <?= $reservation['status'] === 'no_show' ? 'selected' : '' ?>>No show</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <code class="text-sm bg-gray-100 px-2 py-1 rounded"><?= $reservation['confirmation_code'] ?></code>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>" 
                               class="p-1 text-gray-400 hover:text-primary transition-colors" title="Ver detalles">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>/edit" 
                               class="p-1 text-gray-400 hover:text-primary transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<script>
async function changeStatus(id, status) {
    try {
        const formData = new FormData();
        formData.append('reservation_id', id);
        formData.append('status', status);
        
        const response = await fetch(BASE_URL + '/admin/reservations/change-status', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (!data.success) {
            alert('Error: ' + (data.error || 'No se pudo cambiar el estado'));
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cambiar el estado');
        location.reload();
    }
}
</script>
