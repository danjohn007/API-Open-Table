<?php $pageTitle = 'Calendario de Reservaciones'; ?>

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Calendario</h2>
        <p class="text-gray-600">Vista de calendario de reservaciones</p>
    </div>
    
    <div class="flex items-center space-x-4">
        <select id="restaurantSelect" onchange="changeRestaurant(this.value)"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
            <?php foreach ($restaurants as $r): ?>
            <option value="<?= $r['id'] ?>" <?= ($restaurant && $restaurant['id'] == $r['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($r['name']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <a href="<?= BASE_URL ?>/admin/reservations<?= $restaurant ? '?restaurant_id=' . $restaurant['id'] : '' ?>" 
           class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            Vista Lista
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <div id="calendar"></div>
</div>

<!-- Modal de detalles -->
<div id="reservationModal" class="fixed inset-0 z-50 hidden" x-data="{ show: false }">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeModal()"></div>
    <div class="absolute inset-4 md:inset-auto md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 md:w-full md:max-w-lg bg-white rounded-xl shadow-xl">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Detalles de Reservación</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
let calendar;

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '24:00:00',
        slotDuration: '00:30:00',
        allDaySlot: false,
        expandRows: true,
        nowIndicator: true,
        eventClick: function(info) {
            showReservationDetails(info.event);
        },
        events: function(info, successCallback, failureCallback) {
            const restaurantId = document.getElementById('restaurantSelect').value;
            
            fetch(BASE_URL + '/admin/reservations/calendar-events?restaurant_id=' + restaurantId + 
                  '&start=' + info.startStr + '&end=' + info.endStr)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => {
                    console.error('Error:', error);
                    failureCallback(error);
                });
        }
    });
    
    calendar.render();
});

function changeRestaurant(restaurantId) {
    window.location.href = BASE_URL + '/admin/reservations/calendar?restaurant_id=' + restaurantId;
}

function showReservationDetails(event) {
    const modal = document.getElementById('reservationModal');
    const content = document.getElementById('modalContent');
    
    const statusColors = {
        'pending': 'bg-yellow-100 text-yellow-700',
        'confirmed': 'bg-green-100 text-green-700',
        'waiting': 'bg-blue-100 text-blue-700',
        'seated': 'bg-purple-100 text-purple-700',
        'completed': 'bg-gray-100 text-gray-700',
        'cancelled': 'bg-red-100 text-red-700',
        'no_show': 'bg-orange-100 text-orange-700'
    };
    
    content.innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-gray-800">${event.title}</span>
                <span class="px-3 py-1 rounded-full text-sm ${statusColors[event.extendedProps.status] || 'bg-gray-100 text-gray-700'}">
                    ${event.extendedProps.status}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Hora:</span>
                    <span class="ml-2 font-medium">${event.start.toLocaleTimeString('es-MX', {hour: '2-digit', minute: '2-digit'})}</span>
                </div>
                <div>
                    <span class="text-gray-500">Mesa:</span>
                    <span class="ml-2 font-medium">${event.extendedProps.table || 'Sin asignar'}</span>
                </div>
                <div>
                    <span class="text-gray-500">Código:</span>
                    <span class="ml-2 font-medium font-mono">${event.extendedProps.confirmationCode}</span>
                </div>
            </div>
            
            <div class="flex space-x-2 pt-4 border-t">
                <a href="${BASE_URL}/admin/reservations/${event.id}" 
                   class="flex-1 text-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                    Ver Detalles
                </a>
                <a href="${BASE_URL}/admin/reservations/${event.id}/edit" 
                   class="flex-1 text-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Editar
                </a>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('reservationModal').classList.add('hidden');
}
</script>
