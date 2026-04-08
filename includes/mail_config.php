<?php
/**
 * Configuration Email avec Support Gmail
 */

// Paramètres Gmail
define('MAIL_FROM_EMAIL', 'rachnicreation@gmail.com');
define('MAIL_FROM_NAME', 'Clinique Marion');
define('MAIL_SMTP_HOST', 'smtp.gmail.com');
define('MAIL_SMTP_PORT', 587);
define('MAIL_SMTP_USER', 'rachnicreation@gmail.com');
define('MAIL_SMTP_PASSWORD', 'l p v m v i z w f j z j o u k s'); // À remplacer par un mot de passe d'application Gmail

/**
 * Classe Mailer SMTP simple pour Gmail
 */
class SimpleSMTPMailer {
    private $host;
    private $port;
    private $username;
    private $password;
    private $from_email;
    private $from_name;
    private $socket;

    public function __construct($host, $port, $username, $password, $from_email, $from_name) {
        $this->host = $host;
        $this->port = $port;
        $this->username = base64_encode($username);
        $this->password = base64_encode($password);
        $this->from_email = $from_email;
        $this->from_name = $from_name;
    }

    public function send($to_email, $to_name, $subject, $body, $isHTML = true) {
        try {
            // Pour le port 587, utiliser une connexion normale puis STARTTLS
            // Pour le port 465, utiliser SSL direct
            if ($this->port == 587) {
                $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 10);
            } else {
                $this->socket = fsockopen('ssl://' . $this->host, $this->port, $errno, $errstr, 10);
            }

            if (!$this->socket) {
                throw new Exception("Erreur de connexion au serveur SMTP: $errstr ($errno)");
            }

            // Lecture réponse serveur
            $this->getResponse();

            // EHLO
            $this->sendCommand("EHLO " . gethostname());
            $this->getResponse();

            // STARTTLS pour le port 587
            if ($this->port == 587) {
                $this->sendCommand("STARTTLS");
                $this->getResponse();
                stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            }

            // EHLO après STARTTLS (pour port 587)
            if ($this->port == 587) {
                $this->sendCommand("EHLO " . gethostname());
                $this->getResponse();
            }

            // EHLO après STARTTLS
            $this->sendCommand("EHLO " . gethostname());
            $this->getResponse();

            // AUTH
            $this->sendCommand("AUTH LOGIN");
            $this->getResponse();

            $this->sendCommand($this->username);
            $this->getResponse();

            $this->sendCommand($this->password);
            $this->getResponse();

            // MAIL FROM
            $this->sendCommand("MAIL FROM: <" . $this->from_email . ">");
            $this->getResponse();

            // RCPT TO
            $this->sendCommand("RCPT TO: <" . $to_email . ">");
            $this->getResponse();

            // DATA
            $this->sendCommand("DATA");
            $this->getResponse();

            // Construire l'email
            $headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
            $headers .= "To: {$to_name} <{$to_email}>\r\n";
            $headers .= "Subject: " . $this->encodeSubject($subject) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: " . ($isHTML ? "text/html" : "text/plain") . "; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";
            $headers .= "Date: " . date('r') . "\r\n";

            $message = $headers . "\r\n" . $body;

            // Envoyer le message
            fwrite($this->socket, $message . "\r\n.\r\n");
            $this->getResponse();

            // QUIT
            $this->sendCommand("QUIT");
            fclose($this->socket);

            return true;
        } catch (Exception $e) {
            error_log("Erreur SMTP: " . $e->getMessage());
            if ($this->socket) {
                fclose($this->socket);
            }
            return false;
        }
    }

    private function sendCommand($command) {
        fwrite($this->socket, $command . "\r\n");
    }

    private function getResponse() {
        $response = '';
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 1024);
            $response .= $line;
            if (substr($line, 3, 1) === ' ') {
                break;
            }
        }
        return $response;
    }

    private function encodeSubject($subject) {
        return '=?UTF-8?B?' . base64_encode($subject) . '?=';
    }
}

/**
 * Envoyer un email via SMTP
 */
function sendEmail($toEmail, $toName, $subject, $messageBody, $isHTML = true) {
    $mailer = new SimpleSMTPMailer(
        MAIL_SMTP_HOST,
        MAIL_SMTP_PORT,
        MAIL_SMTP_USER,
        MAIL_SMTP_PASSWORD,
        MAIL_FROM_EMAIL,
        MAIL_FROM_NAME
    );

    return $mailer->send($toEmail, $toName, $subject, $messageBody, $isHTML);
}

/**
 * Générer un email HTML pour la confirmation client
 */
function getClientConfirmationEmail($nom, $email, $telephone, $message) {
    $html = "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #0066cc; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h2>Clinique Marion - Confirmation de Contact</h2>
        </div>
        <div class=\"content\">
            <p>Bonjour <strong>" . htmlspecialchars($nom) . "</strong>,</p>
            <p>Merci de nous avoir contactés. Nous avons bien reçu votre message et nous vous recontacterons dans les meilleurs délais.</p>
            <h3>Récapitulatif de votre message:</h3>
            <p><strong>Nom:</strong> " . htmlspecialchars($nom) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Téléphone:</strong> " . htmlspecialchars($telephone) . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
            <br>
            <p>Cordialement,<br><strong>L'équipe de la Clinique Marion</strong></p>
        </div>
        <div class=\"footer\">
            <p>&copy; 2024 Clinique Marion. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>";
    return $html;
}

/**
 * Générer un email HTML pour le déstinataire (admin)
 */
function getAdminNotificationEmail($nom, $email, $telephone, $message) {
    $html = "<!DOCTYPE html>
<html lang=\"fr\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #0066cc; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { background-color: #f5f5f5; padding: 10px; text-align: center; font-size: 12px; border-radius: 0 0 8px 8px; }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h2>Nouveau Message de Contact</h2>
        </div>
        <div class=\"content\">
            <p><strong>Nouveau message reçu de:</strong></p>
            <p><strong>Nom:</strong> " . htmlspecialchars($nom) . "</p>
            <p><strong>Email:</strong> <a href=\"mailto:" . htmlspecialchars($email) . "\">" . htmlspecialchars($email) . "</a></p>
            <p><strong>Téléphone:</strong> " . htmlspecialchars($telephone) . "</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        </div>
        <div class=\"footer\">
            <p>Message automatique - Ne pas répondre</p>
        </div>
    </div>
</body>
</html>";
    return $html;
}
?>
