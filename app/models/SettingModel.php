<?php
/**
 * Modelo de Configuraciones
 */

class SettingModel extends Model {
    protected $table = 'settings';
    protected $primaryKey = 'id';
    
    private static $cache = [];
    
    /**
     * Obtener valor de configuración
     */
    public function get($key, $default = null) {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        
        $setting = $this->findBy('setting_key', $key);
        
        if ($setting) {
            $value = $setting['setting_value'];
            
            // Convertir según el tipo
            switch ($setting['setting_type']) {
                case 'boolean':
                    $value = (bool) $value;
                    break;
                case 'number':
                    $value = is_numeric($value) ? (float) $value : $value;
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
            }
            
            self::$cache[$key] = $value;
            return $value;
        }
        
        return $default;
    }
    
    /**
     * Establecer valor de configuración
     */
    public function set($key, $value, $type = 'text', $group = 'general', $description = '') {
        $setting = $this->findBy('setting_key', $key);
        
        // Convertir valor según tipo
        if ($type === 'json' && is_array($value)) {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }
        
        if ($setting) {
            $this->update($setting['id'], [
                'setting_value' => $value,
                'setting_type' => $type,
                'setting_group' => $group
            ]);
        } else {
            $this->create([
                'setting_key' => $key,
                'setting_value' => $value,
                'setting_type' => $type,
                'setting_group' => $group,
                'description' => $description
            ]);
        }
        
        // Limpiar cache
        unset(self::$cache[$key]);
        
        return true;
    }
    
    /**
     * Obtener todas las configuraciones por grupo
     */
    public function getByGroup($group) {
        return $this->where('setting_group', $group);
    }
    
    /**
     * Obtener todas las configuraciones como array asociativo
     */
    public function getAllAsArray() {
        $settings = $this->all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->get($setting['setting_key']);
        }
        
        return $result;
    }
    
    /**
     * Actualizar múltiples configuraciones
     */
    public function updateBulk($settings) {
        foreach ($settings as $key => $value) {
            $setting = $this->findBy('setting_key', $key);
            if ($setting) {
                $this->set($key, $value, $setting['setting_type'], $setting['setting_group']);
            }
        }
        return true;
    }
    
    /**
     * Limpiar cache
     */
    public function clearCache() {
        self::$cache = [];
    }
}
