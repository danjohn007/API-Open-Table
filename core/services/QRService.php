<?php
/**
 * Servicio de generación de códigos QR
 * Usa la API de QR Server o genera QR localmente
 */

class QRService {
    private $settingModel;
    private $apiEndpoint;
    
    public function __construct() {
        $this->settingModel = new SettingModel();
        $this->apiEndpoint = $this->settingModel->get('qr_api_endpoint', 'https://api.qrserver.com/v1/create-qr-code/');
    }
    
    /**
     * Generar URL del código QR
     */
    public function generateQRUrl($data, $size = 200) {
        $params = [
            'data' => $data,
            'size' => $size . 'x' . $size,
            'format' => 'png',
            'qzone' => 2,
            'color' => '000000',
            'bgcolor' => 'ffffff'
        ];
        
        return $this->apiEndpoint . '?' . http_build_query($params);
    }
    
    /**
     * Generar imagen QR y obtener contenido binario
     */
    public function generateQRImage($data, $size = 200) {
        $url = $this->generateQRUrl($data, $size);
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'ReservationSystem/1.0'
            ]
        ]);
        
        $imageContent = @file_get_contents($url, false, $context);
        
        if ($imageContent === false) {
            return null;
        }
        
        return $imageContent;
    }
    
    /**
     * Guardar código QR en archivo
     */
    public function saveQRCode($data, $filename, $size = 200) {
        $imageContent = $this->generateQRImage($data, $size);
        
        if ($imageContent === null) {
            return false;
        }
        
        $targetDir = UPLOADS_PATH . '/qrcodes';
        if (!is_dir($targetDir)) {
            if (!@mkdir($targetDir, 0755, true)) {
                error_log('QRService: Failed to create directory ' . $targetDir);
                return false;
            }
        }
        
        if (!is_writable($targetDir)) {
            error_log('QRService: Directory not writable ' . $targetDir);
            return false;
        }
        
        $filepath = $targetDir . '/' . $filename;
        
        if (file_put_contents($filepath, $imageContent)) {
            return 'uploads/qrcodes/' . $filename;
        }
        
        return false;
    }
    
    /**
     * Generar QR para una reservación
     */
    public function generateReservationQR($reservation) {
        // Datos a codificar en el QR
        $qrData = json_encode([
            'code' => $reservation['confirmation_code'],
            'date' => $reservation['reservation_date'],
            'time' => $reservation['reservation_time'],
            'guests' => $reservation['party_size'],
            'url' => BASE_URL . '/reservar/consultar?code=' . $reservation['confirmation_code']
        ]);
        
        // Generar nombre de archivo único
        $filename = 'qr_' . $reservation['confirmation_code'] . '.png';
        
        // Guardar QR
        $qrPath = $this->saveQRCode($qrData, $filename);
        
        if ($qrPath) {
            return [
                'path' => $qrPath,
                'url' => BASE_URL . '/public/' . $qrPath,
                'content' => $this->generateQRImage($qrData)
            ];
        }
        
        return null;
    }
    
    /**
     * Generar QR con URL directa para consulta
     */
    public function generateConsultationQR($confirmationCode) {
        $consultUrl = BASE_URL . '/reservar/consultar?code=' . $confirmationCode;
        
        $filename = 'qr_' . $confirmationCode . '.png';
        $qrPath = $this->saveQRCode($consultUrl, $filename);
        
        if ($qrPath) {
            return [
                'path' => $qrPath,
                'url' => BASE_URL . '/public/' . $qrPath,
                'content' => $this->generateQRImage($consultUrl)
            ];
        }
        
        return null;
    }
}
