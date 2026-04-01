<?php
header('Content-Type: application/json');
require_once 'includes/db_config.php';

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
        $stmt = $pdo->prepare("INSERT INTO messages (nom, email, telephone, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $telephone, $message]);
        
        echo json_encode(['status' => 'success', 'message' => 'Votre message a bien été envoyé. Nous vous contacterons sous peu.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenue lors de l\'enregistrement : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée.']);
}
?>
