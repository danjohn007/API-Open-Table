<?php
/**
 * Servicio de correo electrónico
 * Usa la configuración de SMTP definida en el panel de administración
 */

class EmailService {
    private $settingModel;
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromAddress;
    private $fromName;
    
    public function __construct() {
        $this->settingModel = new SettingModel();
        $this->loadSettings();
    }
    
    /**
     * Cargar configuraciones de correo desde la base de datos
     */
    private function loadSettings() {
        $this->host = $this->settingModel->get('mail_host', '');
        $this->port = $this->settingModel->get('mail_port', 587);
        $this->username = $this->settingModel->get('mail_username', '');
        $this->password = $this->settingModel->get('mail_password', '');
        $this->fromAddress = $this->settingModel->get('mail_from_address', '');
        $this->fromName = $this->settingModel->get('mail_from_name', 'Sistema de Reservaciones');
    }
    
    /**
     * Verificar si la configuración de correo está completa
     */
    public function isConfigured() {
        return !empty($this->host) && !empty($this->username) && !empty($this->password) && !empty($this->fromAddress);
    }
    
    /**
     * Enviar correo electrónico
     */
    public function send($to, $subject, $body, $isHtml = true, $attachments = []) {
        if (!$this->isConfigured()) {
            throw new Exception('La configuración de correo no está completa');
        }
        
        // Construir encabezados del correo
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = $isHtml ? 'Content-type: text/html; charset=utf-8' : 'Content-type: text/plain; charset=utf-8';
        $headers[] = 'From: ' . $this->fromName . ' <' . $this->fromAddress . '>';
        $headers[] = 'Reply-To: ' . $this->fromAddress;
        $headers[] = 'X-Mailer: PHP/' . phpversion();
        
        // Si hay archivos adjuntos, usar multipart
        if (!empty($attachments)) {
            return $this->sendWithAttachments($to, $subject, $body, $attachments, $isHtml);
        }
        
        // Intentar usar SMTP si fsockopen está disponible
        if (function_exists('fsockopen') && !empty($this->host)) {
            return $this->sendViaSMTP($to, $subject, $body, $headers);
        }
        
        // Fallback a mail() nativo
        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
    
    /**
     * Enviar correo con archivos adjuntos
     */
    private function sendWithAttachments($to, $subject, $body, $attachments, $isHtml = true) {
        $boundary = md5(uniqid(time()));
        
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'From: ' . $this->fromName . ' <' . $this->fromAddress . '>';
        $headers[] = 'Reply-To: ' . $this->fromAddress;
        $headers[] = 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';
        
        $message = '--' . $boundary . "\r\n";
        $message .= 'Content-Type: ' . ($isHtml ? 'text/html' : 'text/plain') . '; charset=utf-8' . "\r\n";
        $message .= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
        $message .= $body . "\r\n\r\n";
        
        foreach ($attachments as $attachment) {
            if (isset($attachment['content']) && isset($attachment['name'])) {
                $message .= '--' . $boundary . "\r\n";
                $message .= 'Content-Type: ' . ($attachment['type'] ?? 'application/octet-stream') . '; name="' . $attachment['name'] . '"' . "\r\n";
                $message .= 'Content-Disposition: attachment; filename="' . $attachment['name'] . '"' . "\r\n";
                $message .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
                $message .= chunk_split(base64_encode($attachment['content'])) . "\r\n";
            }
        }
        
        $message .= '--' . $boundary . '--';
        
        return mail($to, $subject, $message, implode("\r\n", $headers));
    }
    
    /**
     * Enviar correo vía SMTP
     */
    private function sendViaSMTP($to, $subject, $body, $headers) {
        // Conexión SMTP básica
        $socket = @fsockopen($this->host, $this->port, $errno, $errstr, 30);
        
        if (!$socket) {
            error_log("Error SMTP: No se pudo conectar a {$this->host}:{$this->port} - $errstr ($errno)");
            // Fallback a mail() nativo
            return mail($to, $subject, $body, implode("\r\n", $headers));
        }
        
        // Para servidores con TLS, intentar usar stream_socket_enable_crypto
        if ($this->port == 587) {
            stream_set_blocking($socket, true);
            $greeting = fgets($socket, 515);
            
            // EHLO
            fputs($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
            $this->getSmtpResponse($socket);
            
            // STARTTLS
            fputs($socket, "STARTTLS\r\n");
            $starttlsResponse = $this->getSmtpResponse($socket);
            
            if (strpos($starttlsResponse, '220') !== false) {
                $cryptoEnabled = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                
                if (!$cryptoEnabled) {
                    fclose($socket);
                    error_log("Error SMTP: No se pudo establecer conexión TLS segura");
                    // Fallback a mail() nativo
                    return mail($to, $subject, $body, implode("\r\n", $headers));
                }
                
                // EHLO de nuevo después de TLS
                fputs($socket, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "\r\n");
                $this->getSmtpResponse($socket);
            }
        }
        
        // AUTH LOGIN
        fputs($socket, "AUTH LOGIN\r\n");
        $this->getSmtpResponse($socket);
        
        fputs($socket, base64_encode($this->username) . "\r\n");
        $this->getSmtpResponse($socket);
        
        fputs($socket, base64_encode($this->password) . "\r\n");
        $authResponse = $this->getSmtpResponse($socket);
        
        if (strpos($authResponse, '235') === false) {
            fclose($socket);
            error_log("Error SMTP: Autenticación fallida");
            // Fallback a mail() nativo
            return mail($to, $subject, $body, implode("\r\n", $headers));
        }
        
        // MAIL FROM
        fputs($socket, "MAIL FROM: <{$this->fromAddress}>\r\n");
        $this->getSmtpResponse($socket);
        
        // RCPT TO
        fputs($socket, "RCPT TO: <{$to}>\r\n");
        $this->getSmtpResponse($socket);
        
        // DATA
        fputs($socket, "DATA\r\n");
        $this->getSmtpResponse($socket);
        
        // Mensaje
        $message = implode("\r\n", $headers) . "\r\n";
        $message .= "To: {$to}\r\n";
        $message .= "Subject: {$subject}\r\n\r\n";
        $message .= $body . "\r\n";
        $message .= ".\r\n";
        
        fputs($socket, $message);
        $dataResponse = $this->getSmtpResponse($socket);
        
        // QUIT
        fputs($socket, "QUIT\r\n");
        fclose($socket);
        
        return strpos($dataResponse, '250') !== false;
    }
    
    /**
     * Obtener respuesta SMTP
     */
    private function getSmtpResponse($socket) {
        $response = '';
        while ($str = fgets($socket, 515)) {
            $response .= $str;
            if (substr($str, 3, 1) == ' ') break;
        }
        return $response;
    }
    
    /**
     * Enviar correo de prueba
     */
    public function sendTestEmail($to) {
        $subject = 'Correo de Prueba - Sistema de Reservaciones';
        $body = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { padding: 30px; background: #f9fafb; }
                .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>Prueba de Correo</h1>
                </div>
                <div class="content">
                    <p>¡Hola!</p>
                    <p>Este es un correo de prueba del Sistema de Reservaciones.</p>
                    <p>Si recibiste este mensaje, la configuración de correo está funcionando correctamente.</p>
                    <p><strong>Configuración actual:</strong></p>
                    <ul>
                        <li>Servidor: ' . htmlspecialchars($this->host) . '</li>
                        <li>Puerto: ' . htmlspecialchars($this->port) . '</li>
                        <li>Remitente: ' . htmlspecialchars($this->fromAddress) . '</li>
                    </ul>
                </div>
                <div class="footer">
                    <p>Enviado el ' . date('d/m/Y H:i:s') . '</p>
                    <p>Sistema de Reservaciones</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $this->send($to, $subject, $body);
    }
}
