-- ============================================
-- SQL Update Script for System Enhancements
-- Sistema de Reservaciones de Mesas con OpenTable
-- ============================================

-- Add new settings for policies
INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) VALUES
('terms_and_conditions', '', 'text', 'policies', 'Términos y condiciones del servicio'),
('privacy_policy', '', 'text', 'policies', 'Política de privacidad'),
('cancellation_policy', '', 'text', 'policies', 'Política de cancelación'),
('no_show_policy', '', 'text', 'policies', 'Política de no show')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Add payment settings if not exists
INSERT INTO settings (setting_key, setting_value, setting_type, setting_group, description) VALUES
('payment_currency', 'MXN', 'text', 'payment', 'Moneda de pago'),
('deposit_percentage', '0', 'number', 'payment', 'Porcentaje de depósito requerido')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

-- Ensure avatar column exists in users table
-- This column should already exist from the original schema
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) DEFAULT NULL;

-- Create qrcodes directory placeholder (ensure directory exists in filesystem)
-- Note: The actual directory needs to be created via filesystem: mkdir -p public/uploads/qrcodes

-- Update phone field validation notes (informational only)
-- All phone fields should now validate for 10 digits

-- ============================================
-- VERIFICATION QUERIES
-- Run these to verify the updates were applied
-- ============================================

-- Check settings were added
-- SELECT * FROM settings WHERE setting_group = 'policies';
-- SELECT * FROM settings WHERE setting_key IN ('payment_currency', 'deposit_percentage');

-- Check users table has avatar column
-- DESCRIBE users;
