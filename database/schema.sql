-- ============================================
-- Sistema de Reservaciones de Mesas con OpenTable
-- Base de datos MySQL 5.7
-- ============================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS opentable_reservations 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE opentable_reservations;

-- ============================================
-- TABLA: Configuraciones del Sistema
-- ============================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json', 'color', 'image') DEFAULT 'text',
    setting_group VARCHAR(50) DEFAULT 'general',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Usuarios del Sistema
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'manager', 'staff', 'customer') DEFAULT 'customer',
    avatar VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Restaurantes
-- ============================================
CREATE TABLE IF NOT EXISTS restaurants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(10),
    country VARCHAR(50) DEFAULT 'México',
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    website VARCHAR(255),
    logo VARCHAR(255),
    cover_image VARCHAR(255),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    opening_time TIME NOT NULL,
    closing_time TIME NOT NULL,
    average_time_per_table INT DEFAULT 90 COMMENT 'Tiempo promedio en minutos',
    max_party_size INT DEFAULT 20,
    min_party_size INT DEFAULT 1,
    advance_booking_days INT DEFAULT 30 COMMENT 'Días de anticipación para reservar',
    cancellation_hours INT DEFAULT 24 COMMENT 'Horas antes para cancelar sin cargo',
    opentable_restaurant_id VARCHAR(100),
    opentable_sync_enabled TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Horarios de Restaurantes
-- ============================================
CREATE TABLE IF NOT EXISTS restaurant_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    day_of_week TINYINT NOT NULL COMMENT '0=Domingo, 6=Sábado',
    opening_time TIME NOT NULL,
    closing_time TIME NOT NULL,
    is_closed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Zonas/Áreas del Restaurante
-- ============================================
CREATE TABLE IF NOT EXISTS restaurant_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255),
    is_outdoor TINYINT(1) DEFAULT 0,
    is_vip TINYINT(1) DEFAULT 0,
    is_private TINYINT(1) DEFAULT 0,
    surcharge DECIMAL(10, 2) DEFAULT 0.00,
    is_active TINYINT(1) DEFAULT 1,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Mesas
-- ============================================
CREATE TABLE IF NOT EXISTS tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    area_id INT,
    table_number VARCHAR(20) NOT NULL,
    capacity INT NOT NULL,
    min_capacity INT DEFAULT 1,
    shape ENUM('round', 'square', 'rectangular', 'other') DEFAULT 'square',
    position_x INT DEFAULT 0,
    position_y INT DEFAULT 0,
    is_combinable TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (area_id) REFERENCES restaurant_areas(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Clientes
-- ============================================
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    notes TEXT COMMENT 'Preferencias, alergias, etc.',
    vip_status TINYINT(1) DEFAULT 0,
    total_visits INT DEFAULT 0,
    total_no_shows INT DEFAULT 0,
    total_cancellations INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Reservaciones
-- ============================================
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    confirmation_code VARCHAR(20) NOT NULL UNIQUE,
    restaurant_id INT NOT NULL,
    customer_id INT NOT NULL,
    table_id INT,
    reservation_date DATE NOT NULL,
    reservation_time TIME NOT NULL,
    party_size INT NOT NULL,
    duration_minutes INT DEFAULT 90,
    status ENUM('pending', 'confirmed', 'waiting', 'seated', 'completed', 'cancelled', 'no_show') DEFAULT 'pending',
    source ENUM('internal', 'opentable', 'website', 'phone', 'walkin') DEFAULT 'internal',
    special_requests TEXT,
    occasion VARCHAR(100) COMMENT 'Cumpleaños, aniversario, etc.',
    area_preference VARCHAR(100),
    opentable_reservation_id VARCHAR(100),
    checked_in_at DATETIME,
    seated_at DATETIME,
    completed_at DATETIME,
    cancelled_at DATETIME,
    cancellation_reason TEXT,
    internal_notes TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_reservation_date (reservation_date),
    INDEX idx_restaurant_date (restaurant_id, reservation_date)
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Historial de Cambios de Reservación
-- ============================================
CREATE TABLE IF NOT EXISTS reservation_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    action ENUM('created', 'modified', 'cancelled', 'status_change', 'table_change') NOT NULL,
    old_value TEXT,
    new_value TEXT,
    changed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Bloqueos de Mesas
-- ============================================
CREATE TABLE IF NOT EXISTS table_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_id INT NOT NULL,
    block_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    reason VARCHAR(255),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Fechas Especiales/Cerradas
-- ============================================
CREATE TABLE IF NOT EXISTS special_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT NOT NULL,
    date DATE NOT NULL,
    name VARCHAR(100),
    is_closed TINYINT(1) DEFAULT 0,
    opening_time TIME,
    closing_time TIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Logs de OpenTable
-- ============================================
CREATE TABLE IF NOT EXISTS opentable_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    restaurant_id INT,
    action VARCHAR(100) NOT NULL,
    request_data TEXT,
    response_data TEXT,
    status_code INT,
    is_success TINYINT(1) DEFAULT 0,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Webhooks de OpenTable
-- ============================================
CREATE TABLE IF NOT EXISTS opentable_webhooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    webhook_id VARCHAR(100),
    event_type VARCHAR(100) NOT NULL,
    payload TEXT NOT NULL,
    processed TINYINT(1) DEFAULT 0,
    processed_at DATETIME,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Notificaciones
-- ============================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    customer_id INT,
    type ENUM('email', 'sms', 'whatsapp') NOT NULL,
    template VARCHAR(100) NOT NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    body TEXT,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    sent_at DATETIME,
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================
-- TABLA: Plantillas de Notificación
-- ============================================
CREATE TABLE IF NOT EXISTS notification_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    type ENUM('email', 'sms', 'whatsapp') NOT NULL,
    subject VARCHAR(255),
    body TEXT NOT NULL,
    variables TEXT COMMENT 'Variables disponibles en JSON',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- INSERTAR DATOS DE EJEMPLO
-- ============================================

-- Configuraciones del sistema
INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) VALUES
('site_name', 'Sistema de Reservaciones', 'text', 'general', 'Nombre del sitio'),
('site_logo', '', 'image', 'general', 'Logotipo del sitio'),
('primary_color', '#2563eb', 'color', 'appearance', 'Color primario del sistema'),
('secondary_color', '#1e40af', 'color', 'appearance', 'Color secundario del sistema'),
('accent_color', '#3b82f6', 'color', 'appearance', 'Color de acento'),
('contact_email', 'contacto@ejemplo.com', 'text', 'contact', 'Correo de contacto'),
('contact_phone', '+52 442 123 4567', 'text', 'contact', 'Teléfono de contacto'),
('support_hours', 'Lunes a Viernes 9:00 - 18:00', 'text', 'contact', 'Horario de atención'),
('mail_host', 'smtp.ejemplo.com', 'text', 'mail', 'Servidor SMTP'),
('mail_port', '587', 'number', 'mail', 'Puerto SMTP'),
('mail_username', '', 'text', 'mail', 'Usuario SMTP'),
('mail_password', '', 'text', 'mail', 'Contraseña SMTP'),
('mail_from_address', 'noreply@ejemplo.com', 'text', 'mail', 'Correo de envío'),
('mail_from_name', 'Sistema de Reservaciones', 'text', 'mail', 'Nombre del remitente'),
('paypal_client_id', '', 'text', 'payment', 'PayPal Client ID'),
('paypal_secret', '', 'text', 'payment', 'PayPal Secret'),
('paypal_mode', 'sandbox', 'text', 'payment', 'Modo de PayPal (sandbox/live)'),
('opentable_api_key', '', 'text', 'opentable', 'API Key de OpenTable'),
('opentable_api_secret', '', 'text', 'opentable', 'API Secret de OpenTable'),
('qr_api_endpoint', 'https://api.qrserver.com/v1/create-qr-code/', 'text', 'integrations', 'Endpoint para generar QRs');

-- Usuario administrador (password: admin123)
INSERT INTO users (username, email, password, first_name, last_name, phone, role) VALUES
('admin', 'admin@ejemplo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', '+52 442 123 4567', 'admin');

-- Restaurantes de ejemplo en Querétaro
INSERT INTO restaurants (name, slug, description, address, city, state, postal_code, phone, email, opening_time, closing_time, average_time_per_table) VALUES
('La Casona del Centro', 'la-casona-del-centro', 'Restaurante de cocina tradicional mexicana en el corazón del Centro Histórico de Querétaro', 'Andador 5 de Mayo 10, Centro Histórico', 'Santiago de Querétaro', 'Querétaro', '76000', '+52 442 212 3456', 'reservaciones@lacasona.mx', '13:00:00', '23:00:00', 90),
('Terraza Mirador', 'terraza-mirador', 'Cocina contemporánea con vista panorámica a la ciudad', 'Cerro de las Campanas 15, Centro', 'Santiago de Querétaro', 'Querétaro', '76010', '+52 442 213 7890', 'reservas@terrazamirador.mx', '12:00:00', '22:00:00', 75),
('El Mesón de Tequisquiapan', 'meson-tequisquiapan', 'Cocina regional queretana en ambiente rústico', 'Plaza Principal 8', 'Tequisquiapan', 'Querétaro', '76750', '+52 414 273 4567', 'contacto@mesontequisquiapan.mx', '08:00:00', '21:00:00', 60),
('Viñedos San Sebastián', 'vinedos-san-sebastian', 'Restaurante gourmet en la ruta del vino y queso', 'Carretera San Juan del Río Km 15', 'Ezequiel Montes', 'Querétaro', '76650', '+52 441 277 8901', 'reservas@vinedossansebastian.mx', '11:00:00', '19:00:00', 120);

-- Horarios de restaurantes
INSERT INTO restaurant_schedules (restaurant_id, day_of_week, opening_time, closing_time, is_closed) VALUES
-- La Casona del Centro
(1, 0, '13:00:00', '22:00:00', 0),
(1, 1, '13:00:00', '23:00:00', 0),
(1, 2, '13:00:00', '23:00:00', 0),
(1, 3, '13:00:00', '23:00:00', 0),
(1, 4, '13:00:00', '23:00:00', 0),
(1, 5, '13:00:00', '00:00:00', 0),
(1, 6, '13:00:00', '00:00:00', 0),
-- Terraza Mirador
(2, 0, '12:00:00', '20:00:00', 0),
(2, 1, '00:00:00', '00:00:00', 1),
(2, 2, '12:00:00', '22:00:00', 0),
(2, 3, '12:00:00', '22:00:00', 0),
(2, 4, '12:00:00', '22:00:00', 0),
(2, 5, '12:00:00', '23:00:00', 0),
(2, 6, '12:00:00', '23:00:00', 0);

-- Áreas de restaurantes
INSERT INTO restaurant_areas (restaurant_id, name, description, is_outdoor, is_vip, surcharge, display_order) VALUES
(1, 'Salón Principal', 'Área principal del restaurante', 0, 0, 0, 1),
(1, 'Patio Interior', 'Hermoso patio colonial con fuente', 1, 0, 0, 2),
(1, 'Salón Privado VIP', 'Salón exclusivo para eventos especiales', 0, 1, 500.00, 3),
(2, 'Interior', 'Área climatizada interior', 0, 0, 0, 1),
(2, 'Terraza', 'Terraza con vista panorámica', 1, 0, 100.00, 2),
(3, 'Comedor', 'Comedor tradicional', 0, 0, 0, 1),
(3, 'Jardín', 'Jardín exterior', 1, 0, 0, 2),
(4, 'Salón Principal', 'Salón con vista a viñedos', 0, 0, 0, 1),
(4, 'Terraza de Catas', 'Terraza especial para catas de vino', 1, 1, 200.00, 2);

-- Mesas de restaurantes
INSERT INTO tables (restaurant_id, area_id, table_number, capacity, min_capacity, shape) VALUES
-- La Casona del Centro - Salón Principal
(1, 1, 'M1', 4, 2, 'square'),
(1, 1, 'M2', 4, 2, 'square'),
(1, 1, 'M3', 6, 4, 'rectangular'),
(1, 1, 'M4', 2, 1, 'round'),
(1, 1, 'M5', 2, 1, 'round'),
-- La Casona del Centro - Patio Interior
(1, 2, 'P1', 4, 2, 'round'),
(1, 2, 'P2', 4, 2, 'round'),
(1, 2, 'P3', 8, 6, 'rectangular'),
-- La Casona del Centro - VIP
(1, 3, 'VIP1', 12, 8, 'rectangular'),
-- Terraza Mirador
(2, 4, 'I1', 4, 2, 'square'),
(2, 4, 'I2', 4, 2, 'square'),
(2, 4, 'I3', 6, 4, 'rectangular'),
(2, 5, 'T1', 4, 2, 'round'),
(2, 5, 'T2', 4, 2, 'round'),
(2, 5, 'T3', 2, 1, 'round'),
(2, 5, 'T4', 2, 1, 'round'),
-- El Mesón de Tequisquiapan
(3, 6, 'A1', 4, 2, 'square'),
(3, 6, 'A2', 4, 2, 'square'),
(3, 6, 'A3', 6, 4, 'rectangular'),
(3, 7, 'J1', 4, 2, 'round'),
(3, 7, 'J2', 8, 6, 'rectangular'),
-- Viñedos San Sebastián
(4, 8, 'V1', 4, 2, 'square'),
(4, 8, 'V2', 4, 2, 'square'),
(4, 8, 'V3', 6, 4, 'rectangular'),
(4, 8, 'V4', 8, 6, 'rectangular'),
(4, 9, 'C1', 6, 4, 'round'),
(4, 9, 'C2', 10, 8, 'rectangular');

-- Clientes de ejemplo
INSERT INTO customers (first_name, last_name, email, phone, notes, vip_status) VALUES
('María', 'González López', 'maria.gonzalez@email.com', '+52 442 555 1234', 'Preferencia: mesas cerca de ventana. Alergia a mariscos.', 0),
('Carlos', 'Hernández Ruiz', 'carlos.hernandez@email.com', '+52 442 555 5678', 'Cliente frecuente', 1),
('Ana', 'Martínez Sánchez', 'ana.martinez@email.com', '+52 442 555 9012', '', 0),
('Roberto', 'García Pérez', 'roberto.garcia@email.com', '+52 414 555 3456', 'Celebra su aniversario cada 15 de mayo', 0),
('Laura', 'Rodríguez Díaz', 'laura.rodriguez@email.com', '+52 441 555 7890', 'VIP - propietaria de viñedos locales', 1);

-- Reservaciones de ejemplo
INSERT INTO reservations (confirmation_code, restaurant_id, customer_id, table_id, reservation_date, reservation_time, party_size, status, source, special_requests) VALUES
('RES001QRO', 1, 1, 1, CURDATE(), '14:00:00', 4, 'confirmed', 'website', 'Celebración de cumpleaños'),
('RES002QRO', 1, 2, 6, CURDATE(), '20:00:00', 3, 'confirmed', 'phone', ''),
('RES003QRO', 2, 3, 13, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '19:00:00', 2, 'pending', 'website', 'Mesa en terraza si es posible'),
('RES004QRO', 3, 4, 17, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 4, 'confirmed', 'internal', 'Aniversario de bodas'),
('RES005QRO', 4, 5, 22, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '13:00:00', 6, 'confirmed', 'phone', 'Cata de vinos premium');

-- Plantillas de notificación
INSERT INTO notification_templates (name, type, subject, body, variables) VALUES
('reservation_confirmation', 'email', 'Confirmación de Reservación - {restaurant_name}', 
'Estimado/a {customer_name},\n\nSu reservación ha sido confirmada con los siguientes detalles:\n\nRestaurante: {restaurant_name}\nFecha: {reservation_date}\nHora: {reservation_time}\nPersonas: {party_size}\nCódigo de confirmación: {confirmation_code}\n\nPor favor, llegue 10 minutos antes de su hora de reservación.\n\nGracias por elegirnos.',
'["customer_name", "restaurant_name", "reservation_date", "reservation_time", "party_size", "confirmation_code"]'),

('reservation_reminder', 'email', 'Recordatorio de Reservación - {restaurant_name}',
'Estimado/a {customer_name},\n\nLe recordamos que tiene una reservación para mañana:\n\nRestaurante: {restaurant_name}\nFecha: {reservation_date}\nHora: {reservation_time}\nPersonas: {party_size}\n\n¡Los esperamos!',
'["customer_name", "restaurant_name", "reservation_date", "reservation_time", "party_size"]'),

('reservation_cancellation', 'email', 'Cancelación de Reservación - {restaurant_name}',
'Estimado/a {customer_name},\n\nSu reservación con código {confirmation_code} ha sido cancelada.\n\nSi tiene alguna pregunta, no dude en contactarnos.\n\nSaludos cordiales.',
'["customer_name", "confirmation_code", "restaurant_name"]'),

('reservation_confirmation_sms', 'sms', NULL,
'Reservación confirmada en {restaurant_name} para el {reservation_date} a las {reservation_time}. Código: {confirmation_code}',
'["restaurant_name", "reservation_date", "reservation_time", "confirmation_code"]');
