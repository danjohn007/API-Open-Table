<?php $pageTitle = 'Reservación ' . htmlspecialchars($reservation['confirmation_code']); ?>

<div class="mb-6">
    <a href="<?= BASE_URL ?>/admin/reservations" class="inline-flex items-center text-gray-600 hover:text-primary">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Volver a Reservaciones
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información principal -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm">
            <!-- Header con estado -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?>
                        </h1>
                        <p class="text-gray-500">Código: <span class="font-mono font-medium"><?= htmlspecialchars($reservation['confirmation_code']) ?></span></p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
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
                        <?= ucfirst($reservation['status']) ?>
                    </span>
                </div>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Detalles de la reservación -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Fecha</p>
                        <p class="font-medium text-gray-800"><?= date('d/m/Y', strtotime($reservation['reservation_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Hora</p>
                        <p class="font-medium text-gray-800"><?= date('H:i', strtotime($reservation['reservation_time'])) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Personas</p>
                        <p class="font-medium text-gray-800"><?= $reservation['party_size'] ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duración</p>
                        <p class="font-medium text-gray-800"><?= $reservation['duration_minutes'] ?> min</p>
                    </div>
                </div>
                
                <!-- Restaurante -->
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-1">Restaurante</p>
                    <p class="font-medium text-gray-800"><?= htmlspecialchars($reservation['restaurant_name']) ?></p>
                </div>
                
                <!-- Mesa -->
                <div class="border-t pt-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Mesa asignada</p>
                            <p class="font-medium text-gray-800">
                                <?php if ($reservation['table_number']): ?>
                                    Mesa <?= htmlspecialchars($reservation['table_number']) ?>
                                    <?php if ($reservation['area_name']): ?>
                                        <span class="text-gray-500">(<?= htmlspecialchars($reservation['area_name']) ?>)</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-gray-400">Sin asignar</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php if (!in_array($reservation['status'], ['completed', 'cancelled', 'no_show'])): ?>
                        <button onclick="showAssignModal()" class="text-primary hover:text-secondary text-sm">
                            <?= $reservation['table_number'] ? 'Cambiar mesa' : 'Asignar mesa' ?>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Información de contacto -->
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Contacto del cliente</p>
                    <div class="flex flex-wrap gap-4">
                        <a href="mailto:<?= htmlspecialchars($reservation['customer_email']) ?>" class="flex items-center text-gray-600 hover:text-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <?= htmlspecialchars($reservation['customer_email']) ?>
                        </a>
                        <a href="tel:<?= htmlspecialchars($reservation['customer_phone']) ?>" class="flex items-center text-gray-600 hover:text-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <?= htmlspecialchars($reservation['customer_phone']) ?>
                        </a>
                    </div>
                </div>
                
                <!-- Solicitudes especiales -->
                <?php if ($reservation['special_requests']): ?>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Solicitudes especiales</p>
                    <div class="p-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($reservation['special_requests'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Notas internas -->
                <?php if ($reservation['internal_notes']): ?>
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Notas internas</p>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($reservation['internal_notes'])) ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Historial -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Historial</h3>
            </div>
            <div class="p-6">
                <?php if (empty($history)): ?>
                <p class="text-gray-500 text-center">Sin historial</p>
                <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($history as $entry): ?>
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-primary rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">
                                <span class="font-medium"><?= ucfirst(str_replace('_', ' ', $entry['action'])) ?></span>
                                <?php if ($entry['first_name']): ?>
                                por <?= htmlspecialchars($entry['first_name'] . ' ' . $entry['last_name']) ?>
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($entry['created_at'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Columna de acciones -->
    <div class="space-y-6">
        <!-- Acciones rápidas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
            
            <?php if (!in_array($reservation['status'], ['completed', 'cancelled', 'no_show'])): ?>
            <div class="space-y-3">
                <?php if ($reservation['status'] === 'pending'): ?>
                <button onclick="changeStatus('confirmed')" 
                        class="w-full py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    Confirmar Reservación
                </button>
                <?php endif; ?>
                
                <?php if (in_array($reservation['status'], ['confirmed', 'pending'])): ?>
                <button onclick="checkIn()" 
                        class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    Check-in (Cliente llegó)
                </button>
                <?php endif; ?>
                
                <?php if ($reservation['status'] === 'waiting'): ?>
                <button onclick="changeStatus('seated')" 
                        class="w-full py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                    Sentar Cliente
                </button>
                <?php endif; ?>
                
                <?php if ($reservation['status'] === 'seated'): ?>
                <button onclick="changeStatus('completed')" 
                        class="w-full py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Marcar como Completada
                </button>
                <?php endif; ?>
                
                <hr class="my-2">
                
                <button onclick="changeStatus('no_show')" 
                        class="w-full py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    No Show
                </button>
                
                <button onclick="showCancelModal()" 
                        class="w-full py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    Cancelar Reservación
                </button>
            </div>
            <?php else: ?>
            <p class="text-gray-500 text-center">Esta reservación está <?= $reservation['status'] ?></p>
            <?php endif; ?>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6">
            <a href="<?= BASE_URL ?>/admin/reservations/<?= $reservation['id'] ?>/edit" 
               class="block w-full py-2 text-center border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Editar Reservación
            </a>
        </div>
    </div>
</div>

<script>
const reservationId = <?= $reservation['id'] ?>;

async function changeStatus(status) {
    if (!confirm('¿Cambiar el estado de la reservación?')) return;
    
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('status', status);
    
    const response = await fetch(BASE_URL + '/admin/reservations/change-status', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    if (data.success) {
        location.reload();
    } else {
        alert('Error: ' + data.error);
    }
}

async function checkIn() {
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    
    const response = await fetch(BASE_URL + '/admin/reservations/check-in', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    if (data.success) {
        location.reload();
    } else {
        alert('Error: ' + data.error);
    }
}

function showCancelModal() {
    const reason = prompt('Motivo de la cancelación (opcional):');
    if (reason !== null) {
        cancelReservation(reason);
    }
}

async function cancelReservation(reason) {
    const formData = new FormData();
    formData.append('reservation_id', reservationId);
    formData.append('reason', reason);
    
    const response = await fetch(BASE_URL + '/admin/reservations/cancel', {
        method: 'POST',
        body: formData
    });
    
    const data = await response.json();
    if (data.success) {
        location.reload();
    } else {
        alert('Error: ' + data.error);
    }
}

function showAssignModal() {
    // Implementar modal de asignación de mesa
    alert('Funcionalidad de asignación de mesa - Ir a editar reservación');
    location.href = BASE_URL + '/admin/reservations/' + reservationId + '/edit';
}
</script>
