<?php
/**
 * Modelo de Mesa
 */

class TableModel extends Model {
    protected $table = 'tables';
    
    /**
     * Obtener mesas por restaurante
     */
    public function getByRestaurant($restaurantId) {
        $sql = "SELECT t.*, ra.name as area_name, ra.is_outdoor, ra.is_vip 
                FROM {$this->table} t 
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id 
                WHERE t.restaurant_id = :id AND t.is_active = 1 
                ORDER BY ra.display_order, t.table_number";
        return $this->db->fetchAll($sql, ['id' => $restaurantId]);
    }
    
    /**
     * Obtener mesas disponibles
     */
    public function getAvailable($restaurantId, $date, $time, $partySize, $duration = 90) {
        $endTime = date('H:i:s', strtotime($time) + ($duration * 60));
        
        $sql = "SELECT t.*, ra.name as area_name, ra.is_outdoor, ra.is_vip, ra.surcharge
                FROM {$this->table} t 
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id 
                WHERE t.restaurant_id = :restaurant_id 
                AND t.is_active = 1 
                AND t.capacity >= :party_size
                AND t.min_capacity <= :party_size
                AND t.id NOT IN (
                    SELECT table_id FROM reservations 
                    WHERE table_id IS NOT NULL
                    AND reservation_date = :date 
                    AND status NOT IN ('cancelled', 'no_show', 'completed')
                    AND (
                        (reservation_time <= :time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) > :time)
                        OR (reservation_time < :end_time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) >= :end_time)
                        OR (reservation_time >= :time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) <= :end_time)
                    )
                )
                AND t.id NOT IN (
                    SELECT table_id FROM table_blocks 
                    WHERE block_date = :date 
                    AND (
                        (start_time <= :time AND end_time > :time)
                        OR (start_time < :end_time AND end_time >= :end_time)
                    )
                )
                ORDER BY t.capacity ASC, ra.display_order, t.table_number";
        
        return $this->db->fetchAll($sql, [
            'restaurant_id' => $restaurantId,
            'party_size' => $partySize,
            'date' => $date,
            'time' => $time,
            'end_time' => $endTime
        ]);
    }
    
    /**
     * Verificar disponibilidad de una mesa específica
     */
    public function isAvailable($tableId, $date, $time, $duration = 90, $excludeReservationId = null) {
        $endTime = date('H:i:s', strtotime($time) + ($duration * 60));
        
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE table_id = :table_id 
                AND reservation_date = :date 
                AND status NOT IN ('cancelled', 'no_show', 'completed')
                AND (
                    (reservation_time <= :time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) > :time)
                    OR (reservation_time < :end_time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) >= :end_time)
                    OR (reservation_time >= :time AND ADDTIME(reservation_time, SEC_TO_TIME(duration_minutes * 60)) <= :end_time)
                )";
        
        $params = [
            'table_id' => $tableId,
            'date' => $date,
            'time' => $time,
            'end_time' => $endTime
        ];
        
        if ($excludeReservationId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeReservationId;
        }
        
        $result = $this->db->fetch($sql, $params);
        
        if ($result['count'] > 0) {
            return false;
        }
        
        // Verificar bloqueos
        $sql = "SELECT COUNT(*) as count FROM table_blocks 
                WHERE table_id = :table_id 
                AND block_date = :date 
                AND (
                    (start_time <= :time AND end_time > :time)
                    OR (start_time < :end_time AND end_time >= :end_time)
                )";
        
        $result = $this->db->fetch($sql, [
            'table_id' => $tableId,
            'date' => $date,
            'time' => $time,
            'end_time' => $endTime
        ]);
        
        return $result['count'] == 0;
    }
    
    /**
     * Obtener ocupación de mesas para una fecha
     */
    public function getOccupancy($restaurantId, $date) {
        $sql = "SELECT t.id, t.table_number, t.capacity, ra.name as area_name,
                r.id as reservation_id, r.reservation_time, r.duration_minutes, 
                r.party_size, r.status, c.first_name, c.last_name
                FROM {$this->table} t 
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id 
                LEFT JOIN reservations r ON t.id = r.table_id 
                    AND r.reservation_date = :date 
                    AND r.status NOT IN ('cancelled', 'no_show')
                LEFT JOIN customers c ON r.customer_id = c.id
                WHERE t.restaurant_id = :restaurant_id AND t.is_active = 1
                ORDER BY ra.display_order, t.table_number, r.reservation_time";
        
        return $this->db->fetchAll($sql, [
            'restaurant_id' => $restaurantId,
            'date' => $date
        ]);
    }
    
    /**
     * Bloquear mesa
     */
    public function block($tableId, $date, $startTime, $endTime, $reason = '', $userId = null) {
        return $this->db->insert('table_blocks', [
            'table_id' => $tableId,
            'block_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'reason' => $reason,
            'created_by' => $userId
        ]);
    }
    
    /**
     * Desbloquear mesa
     */
    public function unblock($blockId) {
        return $this->db->delete('table_blocks', 'id = :id', ['id' => $blockId]);
    }
}
