<?php
header('Content-Type: application/json');
require_once 'includes/db_config.php';
require_once 'includes/mail_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['tel'] ?? '';
    $message = $_POST['message'] ?? '';

    if (empty($nom) || empty($email) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs obligatoires.']);
        exit;
    }

    try {
        // Insérer le message en base de données
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, telephone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $message]);
        
        // Envoyer un email de confirmation au client
        $clientEmailBody = getClientConfirmationEmail($nom, $email, $telephone, $message);
        sendEmail($email, $nom, 'Confirmation de votre message - Clinique Marion', $clientEmailBody, true);
        
        // Envoyer une notification à l'admin
        $adminEmailBody = getAdminNotificationEmail($nom, $email, $telephone, $message);
        sendEmail(MAIL_FROM_EMAIL, 'Clinique Marion', 'Nouveau message de contact', $adminEmailBody, true);
        
        echo json_encode(['status' => 'success', 'message' => 'Votre message a bien été envoyé. Nous vous contacterons sous peu.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
}
?>
