<?php
/**
 * Servicio de notificaciones para reservaciones
 * Envía correos con QR de confirmación
 */

class ReservationNotificationService {
    private $emailService;
    private $qrService;
    private $settingModel;
    
    public function __construct() {
        $this->emailService = new EmailService();
        $this->qrService = new QRService();
        $this->settingModel = new SettingModel();
    }
    
    /**
     * Enviar correo de confirmación de reservación con QR
     */
    public function sendConfirmation($reservation) {
        if (empty($reservation['customer_email'])) {
            return false;
        }
        
        // Generar QR
        $qrData = $this->qrService->generateConsultationQR($reservation['confirmation_code']);
        
        // Obtener configuraciones del sitio
        $siteName = $this->settingModel->get('site_name', 'Sistema de Reservaciones');
        $primaryColor = $this->settingModel->get('primary_color', '#2563eb');
        
        // Formatear fecha y hora
        $fecha = date('d/m/Y', strtotime($reservation['reservation_date']));
        $hora = date('H:i', strtotime($reservation['reservation_time']));
        
        $subject = 'Confirmación de Reservación - ' . ($reservation['restaurant_name'] ?? 'Restaurante');
        
        $body = $this->buildConfirmationEmail($reservation, $qrData, $siteName, $primaryColor, $fecha, $hora);
        
        // Adjuntar QR si se generó correctamente
        $attachments = [];
        if ($qrData && isset($qrData['content'])) {
            $attachments[] = [
                'content' => $qrData['content'],
                'name' => 'codigo_qr_' . $reservation['confirmation_code'] . '.png',
                'type' => 'image/png'
            ];
        }
        
        try {
            $result = $this->emailService->send(
                $reservation['customer_email'],
                $subject,
                $body,
                true,
                $attachments
            );
            
            // Registrar notificación en la base de datos
            $this->logNotification($reservation, 'reservation_confirmation', $result);
            
            return $result;
        } catch (Exception $e) {
            error_log('Error enviando correo de confirmación: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Construir HTML del correo de confirmación
     */
    private function buildConfirmationEmail($reservation, $qrData, $siteName, $primaryColor, $fecha, $hora) {
        $qrUrl = $qrData ? $qrData['url'] : '';
        $consultUrl = BASE_URL . '/reservar/consultar?code=' . $reservation['confirmation_code'];
        
        return '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmación de Reservación</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f3f4f6;">
                <tr>
                    <td align="center" style="padding: 40px 20px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            <!-- Header -->
                            <tr>
                                <td style="background-color: ' . htmlspecialchars($primaryColor) . '; padding: 30px; text-align: center;">
                                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;">¡Reservación Confirmada!</h1>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style="padding: 40px 30px;">
                                    <p style="color: #374151; font-size: 16px; margin-bottom: 20px;">
                                        Estimado/a <strong>' . htmlspecialchars($reservation['customer_first_name'] . ' ' . $reservation['customer_last_name']) . '</strong>,
                                    </p>
                                    <p style="color: #374151; font-size: 16px; margin-bottom: 30px;">
                                        Su reservación ha sido confirmada con éxito. A continuación, encontrará los detalles:
                                    </p>
                                    
                                    <!-- Detalles de la reservación -->
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f9fafb; border-radius: 8px; margin-bottom: 30px;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                    <tr>
                                                        <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                                            <span style="color: #6b7280; font-size: 14px;">Código de Confirmación:</span><br>
                                                            <strong style="color: ' . htmlspecialchars($primaryColor) . '; font-size: 20px;">' . htmlspecialchars($reservation['confirmation_code']) . '</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                                            <span style="color: #6b7280; font-size: 14px;">Restaurante:</span><br>
                                                            <strong style="color: #1f2937; font-size: 16px;">' . htmlspecialchars($reservation['restaurant_name'] ?? 'Restaurante') . '</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                                            <span style="color: #6b7280; font-size: 14px;">Fecha:</span><br>
                                                            <strong style="color: #1f2937; font-size: 16px;">' . $fecha . '</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                                                            <span style="color: #6b7280; font-size: 14px;">Hora:</span><br>
                                                            <strong style="color: #1f2937; font-size: 16px;">' . $hora . ' hrs</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding: 10px 0;">
                                                            <span style="color: #6b7280; font-size: 14px;">Número de Personas:</span><br>
                                                            <strong style="color: #1f2937; font-size: 16px;">' . htmlspecialchars($reservation['party_size']) . ' personas</strong>
                                                        </td>
                                                    </tr>
                                                    ' . (!empty($reservation['table_number']) ? '
                                                    <tr>
                                                        <td style="padding: 10px 0; border-top: 1px solid #e5e7eb;">
                                                            <span style="color: #6b7280; font-size: 14px;">Mesa asignada:</span><br>
                                                            <strong style="color: #1f2937; font-size: 16px;">' . htmlspecialchars($reservation['table_number']) . '</strong>
                                                        </td>
                                                    </tr>
                                                    ' : '') . '
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Código QR -->
                                    ' . ($qrUrl ? '
                                    <div style="text-align: center; margin-bottom: 30px;">
                                        <p style="color: #6b7280; font-size: 14px; margin-bottom: 15px;">Presente este código QR al llegar al restaurante:</p>
                                        <img src="' . htmlspecialchars($qrUrl) . '" alt="Código QR" style="width: 180px; height: 180px; border: 1px solid #e5e7eb; border-radius: 8px;">
                                    </div>
                                    ' : '') . '
                                    
                                    <!-- Botón de consulta -->
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td align="center">
                                                <a href="' . htmlspecialchars($consultUrl) . '" style="display: inline-block; background-color: ' . htmlspecialchars($primaryColor) . '; color: #ffffff; text-decoration: none; padding: 14px 30px; border-radius: 8px; font-weight: bold; font-size: 14px;">
                                                    Ver mi Reservación
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p style="color: #6b7280; font-size: 14px; margin-top: 30px; text-align: center;">
                                        Por favor, llegue 10 minutos antes de su hora de reservación.
                                    </p>
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                                        ' . htmlspecialchars($siteName) . '<br>
                                        Solución desarrollada por <a href="https://www.impactosdigitales.com" style="color: ' . htmlspecialchars($primaryColor) . '; text-decoration: none;">ID</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    }
    
    /**
     * Registrar notificación en la base de datos
     */
    private function logNotification($reservation, $template, $success) {
        $db = Database::getInstance();
        
        try {
            $db->insert('notifications', [
                'reservation_id' => $reservation['id'],
                'customer_id' => $reservation['customer_id'],
                'type' => 'email',
                'template' => $template,
                'recipient' => $reservation['customer_email'],
                'subject' => 'Confirmación de Reservación',
                'status' => $success ? 'sent' : 'failed',
                'sent_at' => $success ? date('Y-m-d H:i:s') : null
            ]);
        } catch (Exception $e) {
            error_log('Error registrando notificación: ' . $e->getMessage());
        }
    }
    
    /**
     * Enviar recordatorio de reservación
     */
    public function sendReminder($reservation) {
        // Similar a sendConfirmation pero con mensaje de recordatorio
        if (empty($reservation['customer_email'])) {
            return false;
        }
        
        $siteName = $this->settingModel->get('site_name', 'Sistema de Reservaciones');
        $primaryColor = $this->settingModel->get('primary_color', '#2563eb');
        
        $fecha = date('d/m/Y', strtotime($reservation['reservation_date']));
        $hora = date('H:i', strtotime($reservation['reservation_time']));
        
        $subject = 'Recordatorio: Reservación mañana - ' . ($reservation['restaurant_name'] ?? 'Restaurante');
        
        $body = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Recordatorio de Reservación</title>
        </head>
        <body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: Arial, Helvetica, sans-serif;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f3f4f6;">
                <tr>
                    <td align="center" style="padding: 40px 20px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden;">
                            <tr>
                                <td style="background-color: ' . htmlspecialchars($primaryColor) . '; padding: 30px; text-align: center;">
                                    <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Recordatorio de Reservación</h1>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 40px 30px;">
                                    <p style="color: #374151; font-size: 16px;">
                                        Estimado/a <strong>' . htmlspecialchars($reservation['customer_first_name']) . '</strong>,
                                    </p>
                                    <p style="color: #374151; font-size: 16px;">
                                        Le recordamos que tiene una reservación programada para <strong>mañana</strong>:
                                    </p>
                                    <ul style="color: #374151; font-size: 16px;">
                                        <li><strong>Restaurante:</strong> ' . htmlspecialchars($reservation['restaurant_name'] ?? 'Restaurante') . '</li>
                                        <li><strong>Fecha:</strong> ' . $fecha . '</li>
                                        <li><strong>Hora:</strong> ' . $hora . '</li>
                                        <li><strong>Personas:</strong> ' . htmlspecialchars($reservation['party_size']) . '</li>
                                        <li><strong>Código:</strong> ' . htmlspecialchars($reservation['confirmation_code']) . '</li>
                                    </ul>
                                    <p style="color: #374151; font-size: 16px;">
                                        ¡Los esperamos!
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #f9fafb; padding: 20px; text-align: center;">
                                    <p style="color: #6b7280; font-size: 12px; margin: 0;">
                                        ' . htmlspecialchars($siteName) . '
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
        
        try {
            $result = $this->emailService->send($reservation['customer_email'], $subject, $body);
            $this->logNotification($reservation, 'reservation_reminder', $result);
            return $result;
        } catch (Exception $e) {
            error_log('Error enviando recordatorio: ' . $e->getMessage());
            return false;
        }
    }
}
