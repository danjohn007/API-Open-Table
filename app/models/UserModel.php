<?php
/**
 * Modelo de Usuario
 */

class UserModel extends Model {
    protected $table = 'users';
    
    /**
     * Autenticar usuario
     */
    public function authenticate($username, $password) {
        $user = $this->findBy('username', $username);
        
        if (!$user) {
            $user = $this->findBy('email', $username);
        }
        
        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Crear usuario con hash de contraseña
     */
    public function createUser($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }
    
    /**
     * Actualizar contraseña
     */
    public function updatePassword($userId, $newPassword) {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }
    
    /**
     * Obtener usuarios por rol
     */
    public function getByRole($role) {
        return $this->where('role', $role);
    }
    
    /**
     * Verificar si el email existe
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return (bool) $this->db->fetch($sql, $params);
    }
    
    /**
     * Verificar si el username existe
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT id FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return (bool) $this->db->fetch($sql, $params);
    }
}
