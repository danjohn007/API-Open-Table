<?php
/**
 * Modelo de Restaurante
 */

class RestaurantModel extends Model {
    protected $table = 'restaurants';
    
    /**
     * Obtener restaurante por slug
     */
    public function findBySlug($slug) {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Obtener restaurantes activos
     */
    public function getActive() {
        return $this->where('is_active', 1);
    }
    
    /**
     * Obtener áreas de un restaurante
     */
    public function getAreas($restaurantId) {
        $sql = "SELECT * FROM restaurant_areas WHERE restaurant_id = :id AND is_active = 1 ORDER BY display_order";
        return $this->db->fetchAll($sql, ['id' => $restaurantId]);
    }
    
    /**
     * Obtener mesas de un restaurante
     */
    public function getTables($restaurantId) {
        $sql = "SELECT t.*, ra.name as area_name 
                FROM tables t 
                LEFT JOIN restaurant_areas ra ON t.area_id = ra.id 
                WHERE t.restaurant_id = :id AND t.is_active = 1 
                ORDER BY ra.display_order, t.table_number";
        return $this->db->fetchAll($sql, ['id' => $restaurantId]);
    }
    
    /**
     * Obtener horarios de un restaurante
     */
    public function getSchedules($restaurantId) {
        $sql = "SELECT * FROM restaurant_schedules WHERE restaurant_id = :id ORDER BY day_of_week";
        return $this->db->fetchAll($sql, ['id' => $restaurantId]);
    }
    
    /**
     * Guardar horarios de un restaurante
     */
    public function saveSchedules($restaurantId, $schedules) {
        // Eliminar horarios existentes
        $this->db->delete('restaurant_schedules', 'restaurant_id = :id', ['id' => $restaurantId]);
        
        // Insertar nuevos horarios
        foreach ($schedules as $schedule) {
            $schedule['restaurant_id'] = $restaurantId;
            $this->db->insert('restaurant_schedules', $schedule);
        }
    }
    
    /**
     * Verificar si el restaurante está abierto en un día y hora específicos
     */
    public function isOpen($restaurantId, $date, $time) {
        $dayOfWeek = date('w', strtotime($date));
        
        // Verificar fechas especiales
        $sql = "SELECT * FROM special_dates 
                WHERE restaurant_id = :id AND date = :date";
        $specialDate = $this->db->fetch($sql, ['id' => $restaurantId, 'date' => $date]);
        
        if ($specialDate) {
            if ($specialDate['is_closed']) {
                return false;
            }
            $openTime = $specialDate['opening_time'];
            $closeTime = $specialDate['closing_time'];
        } else {
            // Obtener horario regular
            $sql = "SELECT * FROM restaurant_schedules 
                    WHERE restaurant_id = :id AND day_of_week = :day";
            $schedule = $this->db->fetch($sql, ['id' => $restaurantId, 'day' => $dayOfWeek]);
            
            if (!$schedule || $schedule['is_closed']) {
                return false;
            }
            
            $openTime = $schedule['opening_time'];
            $closeTime = $schedule['closing_time'];
        }
        
        return $time >= $openTime && $time <= $closeTime;
    }
    
    /**
     * Generar slug único
     */
    public function generateSlug($name, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $originalSlug = $slug;
        $counter = 1;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Verificar si el slug existe
     */
    public function slugExists($slug, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return (bool) $this->db->fetch($sql, $params);
    }
    
    /**
     * Obtener estadísticas del restaurante
     */
    public function getStats($restaurantId) {
        $today = date('Y-m-d');
        
        $stats = [
            'tables_count' => 0,
            'total_capacity' => 0,
            'today_reservations' => 0,
            'pending_reservations' => 0,
            'monthly_reservations' => 0
        ];
        
        // Contar mesas y capacidad
        $sql = "SELECT COUNT(*) as count, SUM(capacity) as capacity 
                FROM tables WHERE restaurant_id = :id AND is_active = 1";
        $result = $this->db->fetch($sql, ['id' => $restaurantId]);
        $stats['tables_count'] = $result['count'] ?? 0;
        $stats['total_capacity'] = $result['capacity'] ?? 0;
        
        // Reservaciones de hoy
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE restaurant_id = :id AND reservation_date = :date 
                AND status NOT IN ('cancelled', 'no_show')";
        $result = $this->db->fetch($sql, ['id' => $restaurantId, 'date' => $today]);
        $stats['today_reservations'] = $result['count'] ?? 0;
        
        // Reservaciones pendientes
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE restaurant_id = :id AND status = 'pending'";
        $result = $this->db->fetch($sql, ['id' => $restaurantId]);
        $stats['pending_reservations'] = $result['count'] ?? 0;
        
        // Reservaciones del mes
        $firstDay = date('Y-m-01');
        $lastDay = date('Y-m-t');
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE restaurant_id = :id 
                AND reservation_date BETWEEN :first AND :last
                AND status NOT IN ('cancelled', 'no_show')";
        $result = $this->db->fetch($sql, [
            'id' => $restaurantId, 
            'first' => $firstDay, 
            'last' => $lastDay
        ]);
        $stats['monthly_reservations'] = $result['count'] ?? 0;
        
        return $stats;
    }
}
