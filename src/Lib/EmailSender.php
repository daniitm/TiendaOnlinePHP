<?php
namespace Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configureMailer();
    }

    private function configureMailer() {
        try {
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['SMTP_HOST'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $_ENV['SMTP_USERNAME'];
            $this->mailer->Password = $_ENV['SMTP_PASSWORD'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $_ENV['SMTP_PORT'];
        } catch (Exception $e) {
            error_log("Error configurando SMTP: " . $e->getMessage());
        }
    }

    public function sendOrderConfirmation($customerEmail, $orderNumber, $cart, $totalCost, $customerName) {
        try {
            $this->mailer->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $this->mailer->addAddress($customerEmail, $customerName);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = "Confirmación de pedido #$orderNumber";
            
            $body = $this->generateEmailBody($cart, $orderNumber, $customerName, $totalCost);
            $this->mailer->Body = $body;

            if ($this->mailer->send()) {
                error_log("Correo enviado correctamente a $customerEmail");
                return true;
            } else {
                error_log("Error al enviar correo: " . $this->mailer->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log("Excepción al enviar correo: " . $e->getMessage());
            return false;
        }
    }

    private function generateEmailBody($cart, $orderNumber, $customerName, $totalCost) {
        $productsList = "<table border='1'><tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr>";
        foreach ($cart as $item) {
            $productsList .= "<tr>
                <td>{$item['nombre']}</td>
                <td>{$item['cantidad']}</td>
                <td>" . number_format($item['precio'], 2) . "€</td>
            </tr>";
        }
        $productsList .= "</table>";

        return "
        <html>
        <body>
            <h2>Confirmación de Pedido #$orderNumber</h2>
            <p>Hola $customerName,</p>
            <p>Gracias por tu compra. Aquí están los detalles:</p>
            $productsList
            <p><strong>Total del pedido:</strong> " . number_format($totalCost, 2) . "€</p>
        </body>
        </html>
        ";
    }

    public function sendPasswordResetEmail($email, $resetLink) {
        try {
            $this->mailer->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = "Restablecimiento de contraseña";
            
            $body = $this->generatePasswordResetEmailBody($resetLink);
            $this->mailer->Body = $body;

            if ($this->mailer->send()) {
                error_log("Correo de restablecimiento enviado correctamente a $email");
                return true;
            } else {
                error_log("Error al enviar correo de restablecimiento: " . $this->mailer->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log("Excepción al enviar correo de restablecimiento: " . $e->getMessage());
            return false;
        }
    }

    private function generatePasswordResetEmailBody($resetLink) {
        return "
        <html>
        <body>
            <h2>Restablecimiento de Contraseña</h2>
            <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:</p>
            <p><a href='$resetLink'>Restablecer Contraseña</a></p>
            <p>Si no has solicitado este cambio, puedes ignorar este correo.</p>
        </body>
        </html>
        ";
    }

    public function sendVerificationEmail($email, $verificationLink) {
        try {
            $this->mailer->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = "Verifica tu cuenta";
            
            $body = $this->generateVerificationEmailBody($verificationLink);
            $this->mailer->Body = $body;
    
            if ($this->mailer->send()) {
                error_log("Correo de verificación enviado correctamente a $email");
                return true;
            } else {
                error_log("Error al enviar correo de verificación: " . $this->mailer->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log("Excepción al enviar correo de verificación: " . $e->getMessage());
            return false;
        }
    }
    
    private function generateVerificationEmailBody($verificationLink) {
        return "
        <html>
        <body>
            <h2>Verifica tu cuenta</h2>
            <p>Gracias por registrarte. Por favor, haz clic en el siguiente enlace para verificar tu cuenta:</p>
            <p><a href='$verificationLink'>Verificar cuenta</a></p>
        </body>
        </html>
        ";
    }
}