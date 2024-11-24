<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailSender {
    private $mailer;
    private $config;

    public function __construct() {
        $this->config = require_once 'config/mail.php';
        $this->mailer = new PHPMailer(true);

        // Configuración del servidor SMTP
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->config['SMTP_HOST'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->config['SMTP_USER'];
        $this->mailer->Password = $this->config['SMTP_PASS'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $this->config['SMTP_PORT'];
    }

    public function sendRecoveryEmail($to, $token) {
        try {
            // Construir la URL con el directorio del proyecto
            $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/PROYECTO";
            $resetLink = $baseUrl . "/reset-password.php?token=" . $token;

            // Añade el destinatario
            $this->mailer->addAddress($to);

            // Configura la información del correo
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Recuperación de contraseña';
            $this->mailer->Body = $this->getEmailTemplate($resetLink);

            // Usar el email del usuario como remitente
            $this->mailer->setFrom($to, $this->config['SMTP_FROM_NAME']);

            // Limpiar destinatarios anteriores
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);

            // Envía el correo
            $this->mailer->send();
            
            // Limpiar después del envío para el siguiente uso
            $this->mailer->clearAddresses();
            return true;
        } catch (Exception $e) {
            throw new Exception("Error al enviar email: " . $this->mailer->ErrorInfo);
        }
    }

    private function getEmailTemplate($resetLink) {
        return '
        <!DOCTYPE html>
        <html>
        <body>
            <h2>Recuperación de contraseña</h2>
            <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace:</p>
            <p><a href="' . $resetLink . '">Restablecer contraseña</a></p>
            <p>Este enlace expirará en 1 hora.</p>
            <p>Si no solicitaste este cambio, ignora este mensaje.</p>
        </body>
        </html>';
    }
}