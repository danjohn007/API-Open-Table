<?php
/**
 * Modelo de Reservación
 */

class ReservationModel extends Model {
    protected $table = 'reservations';
    
    /**
     * Generar código de confirmación único
     */
    public function generateConfirmationCode() {
        do {
            $code = 'RES' . strtoupper(substr(uniqid(), -6)) . rand(10, 99);
        } while ($this->findBy('confirmation_code', $code));
        
        return $code;
    }
    
    /**
     * Crear reservación
     */
    public function createReservation($data) {
        $data['confirmation_code'] = $this->generateConfirmationCode();
        $data['status'] = $data['status'] ?? 'pending';
        
        $id = $this->create($data);
        
        // Registrar en historial
        $this->logHistory($id, 'created', null, json_encode($data));
        
        return $id;
    }
    
    /**
     * Obtener reservación por código de confirmación
     */
    public function findByCode($code) {
        $sql = "SELECT r.*, 
                rest.name as restaurant_name, rest.phone as restaurant_phone, rest.address as restaurant_address,
                c.first_name as customer_first_name, c.last_name as customer_last_name, 
                c.email as customer_email, c.phone as customer_phone,
                t.table_number, ra.name as area_name
                FROM {$this->table} r
                JOIN restaurants rest ON r.restaurant_id = rest.id
                JOIN customers c ON r.customer_id = c.id
                LEFT JOIN tables t ON r.table_id = t.id
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id
                WHERE r.confirmation_code = :code";
        
        return $this->db->fetch($sql, ['code' => $code]);
    }
    
    /**
     * Obtener reservaciones de un restaurante por fecha
     */
    public function getByRestaurantAndDate($restaurantId, $date, $status = null) {
        $sql = "SELECT r.*, 
                c.first_name as customer_first_name, c.last_name as customer_last_name, 
                c.email as customer_email, c.phone as customer_phone,
                t.table_number, ra.name as area_name
                FROM {$this->table} r
                JOIN customers c ON r.customer_id = c.id
                LEFT JOIN tables t ON r.table_id = t.id
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id
                WHERE r.restaurant_id = :restaurant_id 
                AND r.reservation_date = :date";
        
        $params = ['restaurant_id' => $restaurantId, 'date' => $date];
        
        if ($status) {
            $sql .= " AND r.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY r.reservation_time ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Obtener reservaciones pendientes
     */
    public function getPending($restaurantId = null) {
        $sql = "SELECT r.*, 
                rest.name as restaurant_name,
                c.first_name as customer_first_name, c.last_name as customer_last_name, 
                c.phone as customer_phone
                FROM {$this->table} r
                JOIN restaurants rest ON r.restaurant_id = rest.id
                JOIN customers c ON r.customer_id = c.id
                WHERE r.status = 'pending'";
        
        $params = [];
        
        if ($restaurantId) {
            $sql .= " AND r.restaurant_id = :restaurant_id";
            $params['restaurant_id'] = $restaurantId;
        }
        
        $sql .= " ORDER BY r.reservation_date, r.reservation_time";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Obtener reservaciones de hoy
     */
    public function getToday($restaurantId) {
        return $this->getByRestaurantAndDate($restaurantId, date('Y-m-d'));
    }
    
    /**
     * Cambiar estado de reservación
     */
    public function changeStatus($id, $newStatus, $userId = null, $reason = null) {
        $reservation = $this->find($id);
        $oldStatus = $reservation['status'];
        
        $updateData = ['status' => $newStatus];
        
        switch ($newStatus) {
            case 'seated':
                $updateData['seated_at'] = date('Y-m-d H:i:s');
                break;
            case 'completed':
                $updateData['completed_at'] = date('Y-m-d H:i:s');
                break;
            case 'cancelled':
                $updateData['cancelled_at'] = date('Y-m-d H:i:s');
                if ($reason) {
                    $updateData['cancellation_reason'] = $reason;
                }
                break;
        }
        
        $this->update($id, $updateData);
        
        // Registrar en historial
        $this->logHistory($id, 'status_change', $oldStatus, $newStatus, $userId);
        
        return true;
    }
    
    /**
     * Check-in de cliente
     */
    public function checkIn($id, $userId = null) {
        $this->update($id, ['checked_in_at' => date('Y-m-d H:i:s')]);
        $this->changeStatus($id, 'waiting', $userId);
        return true;
    }
    
    /**
     * Asignar mesa
     */
    public function assignTable($id, $tableId, $userId = null) {
        $reservation = $this->find($id);
        $oldTableId = $reservation['table_id'];
        
        $this->update($id, ['table_id' => $tableId]);
        
        // Registrar en historial
        $this->logHistory($id, 'table_change', $oldTableId, $tableId, $userId);
        
        return true;
    }
    
    /**
     * Cancelar reservación
     */
    public function cancel($id, $reason = null, $userId = null) {
        return $this->changeStatus($id, 'cancelled', $userId, $reason);
    }
    
    /**
     * Registrar historial
     */
    public function logHistory($reservationId, $action, $oldValue = null, $newValue = null, $userId = null) {
        $this->db->insert('reservation_history', [
            'reservation_id' => $reservationId,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'changed_by' => $userId
        ]);
    }
    
    /**
     * Obtener historial de reservación
     */
    public function getHistory($reservationId) {
        $sql = "SELECT rh.*, u.first_name, u.last_name 
                FROM reservation_history rh
                LEFT JOIN users u ON rh.changed_by = u.id
                WHERE rh.reservation_id = :id
                ORDER BY rh.created_at DESC";
        
        return $this->db->fetchAll($sql, ['id' => $reservationId]);
    }
    
    /**
     * Obtener historial de un cliente
     */
    public function getCustomerHistory($customerId) {
        $sql = "SELECT r.*, 
                rest.name as restaurant_name, rest.city,
                t.table_number
                FROM {$this->table} r
                JOIN restaurants rest ON r.restaurant_id = rest.id
                LEFT JOIN tables t ON r.table_id = t.id
                WHERE r.customer_id = :customer_id
                ORDER BY r.reservation_date DESC, r.reservation_time DESC";
        
        return $this->db->fetchAll($sql, ['customer_id' => $customerId]);
    }
    
    /**
     * Verificar conflictos de horario
     */
    public function hasConflict($restaurantId, $date, $time, $tableId = null, $duration = 90, $excludeId = null) {
        if (!$tableId) {
            return false;
        }
        
        $tableModel = new TableModel();
        return !$tableModel->isAvailable($tableId, $date, $time, $duration, $excludeId);
    }
    
    /**
     * Obtener estadísticas de reservaciones
     */
    public function getStats($restaurantId, $startDate, $endDate) {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_shows,
                SUM(party_size) as total_guests
                FROM {$this->table}
                WHERE restaurant_id = :restaurant_id
                AND reservation_date BETWEEN :start_date AND :end_date";
        
        return $this->db->fetch($sql, [
            'restaurant_id' => $restaurantId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    /**
     * Obtener reservaciones por rango de fechas
     */
    public function getByDateRange($restaurantId, $startDate, $endDate) {
        $sql = "SELECT r.*, 
                c.first_name as customer_first_name, c.last_name as customer_last_name,
                t.table_number
                FROM {$this->table} r
                JOIN customers c ON r.customer_id = c.id
                LEFT JOIN tables t ON r.table_id = t.id
                WHERE r.restaurant_id = :restaurant_id
                AND r.reservation_date BETWEEN :start_date AND :end_date
                AND r.status NOT IN ('cancelled', 'no_show')
                ORDER BY r.reservation_date, r.reservation_time";
        
        return $this->db->fetchAll($sql, [
            'restaurant_id' => $restaurantId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    /**
     * Obtener próximas reservaciones que necesitan recordatorio
     */
    public function getUpcomingForReminder($hoursAhead = 24) {
        $targetDate = date('Y-m-d', strtotime("+{$hoursAhead} hours"));
        
        $sql = "SELECT r.*, 
                rest.name as restaurant_name, rest.phone as restaurant_phone,
                c.first_name as customer_first_name, c.last_name as customer_last_name, 
                c.email as customer_email, c.phone as customer_phone
                FROM {$this->table} r
                JOIN restaurants rest ON r.restaurant_id = rest.id
                JOIN customers c ON r.customer_id = c.id
                WHERE r.reservation_date = :date
                AND r.status IN ('pending', 'confirmed')
                AND r.id NOT IN (
                    SELECT reservation_id FROM notifications 
                    WHERE template = 'reservation_reminder' 
                    AND status = 'sent'
                )";
        
        return $this->db->fetchAll($sql, ['date' => $targetDate]);
    }
}
