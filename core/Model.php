<?php
/**
 * Modelo Base
 */

abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtener todos los registros
     */
    public function all($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Obtener un registro por ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }
    
    /**
     * Obtener registros por condición
     */
    public function where($column, $value, $operator = '=') {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
        return $this->db->fetchAll($sql, ['value' => $value]);
    }
    
    /**
     * Obtener un registro por condición
     */
    public function findBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1";
        return $this->db->fetch($sql, ['value' => $value]);
    }
    
    /**
     * Crear un nuevo registro
     */
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    /**
     * Actualizar un registro
     */
    public function update($id, $data) {
        return $this->db->update($this->table, $data, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    /**
     * Eliminar un registro
     */
    public function delete($id) {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }
    
    /**
     * Contar registros
     */
    public function count($where = null, $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->fetch($sql, $params);
        return $result['total'] ?? 0;
    }
    
    /**
     * Consulta personalizada
     */
    public function query($sql, $params = []) {
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Consulta personalizada - un registro
     */
    public function queryOne($sql, $params = []) {
        return $this->db->fetch($sql, $params);
    }
    
    /**
     * Paginación
     */
    public function paginate($page = 1, $perPage = 10, $where = null, $params = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        $total = $this->count($where, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}
