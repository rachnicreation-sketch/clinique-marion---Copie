<?php
/**
 * Script de test pour vérifier la configuration email
 * Accès: http://localhost/clinique-marion%20-%20Copie/test_email.php
 */

header('Content-Type: text/html; charset=UTF-8');
require_once 'includes/mail_config.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email - Clinique Marion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0066cc;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 10px;
        }
        .form-group {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background-color: #0066cc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0052a3;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success {
            background-color: #c8e6c9;
            border-left-color: #4caf50;
        }
        .error {
            background-color: #ffcdd2;
            border-left-color: #f44336;
        }
        .config-info {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        code {
            background-color: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test d'Envoi d'Email</h1>
        
        <div class="config-info">
            <strong>📧 Email configuré:</strong> <code><?php echo MAIL_FROM_EMAIL; ?></code>
            <br>
            <strong>🔐 Mot de passe configuré:</strong> 
            <?php 
                if (MAIL_SMTP_PASSWORD === 'your_app_password_here') {
                    echo '<span style="color: red;">❌ NON (vous devez le mettre à jour)</span>';
                } else {
                    echo '<span style="color: green;">✅ OUI</span>';
                }
            ?>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $test_email = $_POST['test_email'] ?? '';
            $test_name = $_POST['test_name'] ?? 'Testeur';
            $test_message = $_POST['test_message'] ?? 'Ceci est un email de test';

            if (empty($test_email)) {
                echo '<div class="info-box error">❌ Erreur: Veuillez entrer une adresse email</div>';
            } else {
                echo '<div class="info-box" style="background-color: #f0f4ff;"><strong>📨 Envoi en cours...</strong></div>';
                
                // Génération du contenu HTML
                $htmlBody = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #0066cc; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Email de Test - Clinique Marion</h2>
        </div>
        <div class='content'>
            <p>Bonjour,</p>
            <p>Ceci est un <strong>email de test</strong> pour vérifier que le système d'envoi d'emails fonctionne correctement.</p>
            <p><strong>Message de test:</strong></p>
            <p>" . htmlspecialchars($test_message) . "</p>
            <br>
            <p>Si vous recevez cet email, la configuration est correcte! ✅</p>
            <p>Cordialement,<br>Clinique Marion</p>
        </div>
    </div>
</body>
</html>";

                // Envoi de l'email
                $success = sendEmail($test_email, $test_name, '[TEST] Clinique Marion - Email de Test', $htmlBody, true);

                if ($success) {
                    echo '<div class="info-box success">✅ <strong>Succès!</strong> Email envoyé à ' . htmlspecialchars($test_email) . '</div>';
                    echo '<div class="info-box">Vérifiez votre boîte de réception (et le dossier spam). L\'email devrait arriver dans quelques secondes.</div>';
                } else {
                    echo '<div class="info-box error">❌ <strong>Erreur!</strong> L\'email n\'a pas pu être envoyé.</div>';
                    echo '<div class="info-box" style="background-color: #fff8e1;">
                        <strong>Vérifiez:</strong>
                        <ul>
                            <li>Le mot de passe d\'application Gmail est correctement configuré</li>
                            <li>Vérifiez <code>includes/mail_config.php</code> ligne 12</li>
                            <li>Consultez le log d\'erreur PHP: <code>c:\wamp64\logs\php_error.log</code></li>
                        </ul>
                    </div>';
                }
            }
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label for="test_name">Votre nom:</label>
                <input type="text" id="test_name" name="test_name" value="Testeur" required>
            </div>

            <div class="form-group">
                <label for="test_email">Email de test (où recevoir l'email):</label>
                <input type="email" id="test_email" name="test_email" placeholder="votre.email@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="test_message">Message de test:</label>
                <textarea id="test_message" name="test_message" placeholder="Votre message de test...">Ceci est un email de test pour la configuration SMTP Gmail</textarea>
            </div>

            <button type="submit">📧 Envoyer un email de test</button>
        </form>

        <div class="info-box">
            <h3>📝 Instructions:</h3>
            <ol>
                <li>Assurez-vous d'avoir configuré le mot de passe d'application dans <code>includes/mail_config.php</code></li>
                <li>Entrez votre email de test</li>
                <li>Cliquez sur "Envoyer un email de test"</li>
                <li>Vérifiez que vous recevez l'email</li>
            </ol>
        </div>

        <div class="info-box" style="background-color: #f3e5f5;">
            <h3>🔐 Configuration actuelle:</h3>
            <p>
                <strong>Host:</strong> <code><?php echo MAIL_SMTP_HOST; ?></code><br>
                <strong>Port:</strong> <code><?php echo MAIL_SMTP_PORT; ?></code><br>
                <strong>Email:</strong> <code><?php echo MAIL_SMTP_USER; ?></code><br>
                <strong>Nom:</strong> <code><?php echo MAIL_FROM_NAME; ?></code>
            </p>
        </div>
    </div>
</body>
</html>
