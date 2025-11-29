<?php
/**
 * Modelo de Cliente
 */

class CustomerModel extends Model {
    protected $table = 'customers';
    
    /**
     * Buscar o crear cliente por email
     */
    public function findOrCreate($data) {
        $customer = $this->findBy('email', $data['email']);
        
        if ($customer) {
            // Actualizar datos si es necesario
            $this->update($customer['id'], [
                'phone' => $data['phone'] ?? $customer['phone'],
                'first_name' => $data['first_name'] ?? $customer['first_name'],
                'last_name' => $data['last_name'] ?? $customer['last_name']
            ]);
            return $customer['id'];
        }
        
        return $this->create($data);
    }
    
    /**
     * Buscar cliente por teléfono
     */
    public function findByPhone($phone) {
        return $this->findBy('phone', $phone);
    }
    
    /**
     * Actualizar estadísticas del cliente
     */
    public function updateStats($customerId, $type) {
        $customer = $this->find($customerId);
        
        switch ($type) {
            case 'visit':
                $this->update($customerId, [
                    'total_visits' => $customer['total_visits'] + 1
                ]);
                break;
            case 'no_show':
                $this->update($customerId, [
                    'total_no_shows' => $customer['total_no_shows'] + 1
                ]);
                break;
            case 'cancellation':
                $this->update($customerId, [
                    'total_cancellations' => $customer['total_cancellations'] + 1
                ]);
                break;
        }
    }
    
    /**
     * Obtener historial de reservaciones de un cliente
     */
    public function getReservationHistory($customerId) {
        $reservationModel = new ReservationModel();
        return $reservationModel->getCustomerHistory($customerId);
    }
    
    /**
     * Buscar clientes
     */
    public function search($query) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE first_name LIKE :query 
                OR last_name LIKE :query 
                OR email LIKE :query 
                OR phone LIKE :query
                ORDER BY first_name, last_name
                LIMIT 20";
        
        return $this->db->fetchAll($sql, ['query' => "%{$query}%"]);
    }
    
    /**
     * Obtener clientes VIP
     */
    public function getVipCustomers() {
        return $this->where('vip_status', 1);
    }
    
    /**
     * Marcar como VIP
     */
    public function setVipStatus($customerId, $status) {
        return $this->update($customerId, ['vip_status' => $status ? 1 : 0]);
    }
}
