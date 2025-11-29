<?php $pageTitle = 'Dashboard'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Restaurantes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['total_restaurants'] ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Reservaciones Hoy</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['today_reservations'] ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Pendientes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['pending_reservations'] ?></p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Clientes</p>
                <p class="text-3xl font-bold text-gray-800 mt-1"><?= $stats['total_customers'] ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Chart: Reservaciones últimos 7 días -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservaciones - Últimos 7 días</h3>
        <canvas id="reservationsChart" height="200"></canvas>
    </div>
    
    <!-- Chart: Estados de reservaciones -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Estado de Reservaciones (Este mes)</h3>
        <canvas id="statusChart" height="200"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Reservaciones de hoy -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Reservaciones de Hoy</h3>
                <a href="<?= BASE_URL ?>/admin/reservations" class="text-primary hover:text-secondary text-sm font-medium">Ver todas →</a>
            </div>
        </div>
        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
            <?php 
            $allToday = [];
            foreach ($todayReservations as $restaurantId => $reservations) {
                foreach ($reservations as $r) {
                    $allToday[] = $r;
                }
            }
            usort($allToday, function($a, $b) {
                return strcmp($a['reservation_time'], $b['reservation_time']);
            });
            
            if (empty($allToday)): ?>
            <div class="p-6 text-center text-gray-500">
                No hay reservaciones para hoy
            </div>
            <?php else: ?>
            <?php foreach (array_slice($allToday, 0, 10) as $reservation): ?>
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                                <?= strtoupper(substr($reservation['customer_first_name'], 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?>
                            </p>
                            <p class="text-xs text-gray-500">
                                <?= date('H:i', strtotime($reservation['reservation_time'])) ?> - <?= $reservation['party_size'] ?> personas
                                <?php if ($reservation['table_number']): ?>
                                    - Mesa <?= htmlspecialchars($reservation['table_number']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full 
                        <?php 
                        switch($reservation['status']) {
                            case 'confirmed': echo 'bg-green-100 text-green-700'; break;
                            case 'pending': echo 'bg-yellow-100 text-yellow-700'; break;
                            case 'seated': echo 'bg-purple-100 text-purple-700'; break;
                            case 'waiting': echo 'bg-blue-100 text-blue-700'; break;
                            default: echo 'bg-gray-100 text-gray-700';
                        }
                        ?>">
                        <?= ucfirst($reservation['status']) ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Reservaciones pendientes -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Pendientes de Confirmación</h3>
                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                    <?= count($pendingReservations) ?>
                </span>
            </div>
        </div>
        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
            <?php if (empty($pendingReservations)): ?>
            <div class="p-6 text-center text-gray-500">
                No hay reservaciones pendientes
            </div>
            <?php else: ?>
            <?php foreach (array_slice($pendingReservations, 0, 10) as $reservation): ?>
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) ?>
                        </p>
                        <p class="text-xs text-gray-500">
                            <?= htmlspecialchars($reservation['restaurant_name']) ?> - 
                            <?= date('d/m/Y', strtotime($reservation['reservation_date'])) ?> a las 
                            <?= date('H:i', strtotime($reservation['reservation_time'])) ?>
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="confirmReservation(<?= $reservation['id'] ?>)" 
                                class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart de reservaciones
    const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
    new Chart(reservationsCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['labels']) ?>,
            datasets: [{
                label: 'Reservaciones',
                data: <?= json_encode($chartData['reservations']) ?>,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    
    // Chart de estados
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completadas', 'Canceladas', 'No Show'],
            datasets: [{
                data: [
                    <?= $chartData['statusCounts']['completed'] ?>,
                    <?= $chartData['statusCounts']['cancelled'] ?>,
                    <?= $chartData['statusCounts']['no_show'] ?>
                ],
                backgroundColor: ['#22c55e', '#ef4444', '#f97316']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

async function confirmReservation(id) {
    try {
        const response = await fetch(BASE_URL + '/admin/reservations/change-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reservation_id=' + id + '&status=confirmed'
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al confirmar la reservación');
    }
}
</script>
