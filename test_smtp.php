<?php
/**
 * Test de connexion SMTP Gmail
 * Accès: http://localhost/clinique-marion%20-%20Copie/test_smtp.php
 */

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SMTP Gmail - Clinique Marion</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
        }
        .test-section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .test-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 12px 0;
            padding: 10px;
            border-radius: 6px;
            background: white;
        }
        .status {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            flex-shrink: 0;
        }
        .success {
            background-color: #4caf50;
        }
        .error {
            background-color: #f44336;
        }
        .warning {
            background-color: #ff9800;
        }
        .info {
            background-color: #2196f3;
        }
        .label {
            flex: 1;
            color: #333;
        }
        .value {
            color: #666;
            font-family: monospace;
            font-size: 13px;
            background: #f0f0f0;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .success-box {
            background-color: #c8e6c9;
            border: 1px solid #4caf50;
            color: #2e7d32;
        }
        .error-box {
            background-color: #ffcdd2;
            border: 1px solid #f44336;
            color: #c62828;
        }
        .code-block {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Consolas', 'Monaco', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .link {
            color: #667eea;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
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
            min-height: 80px;
        }
        button {
            background-color: #667eea;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        button:hover {
            background-color: #5a67d8;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Test Connexion SMTP Gmail</h1>
        <p class="subtitle">Test de l'envoi d'emails via Gmail SMTP</p>

        <?php
        require_once 'includes/mail_config.php';

        $test_results = [];
        $smtp_connected = false;
        $smtp_error = '';

        // Test de connexion SMTP basique
        try {
            $socket = fsockopen('smtp.gmail.com', 587, $errno, $errstr, 10);
            if ($socket) {
                $response = fgets($socket, 1024);
                fclose($socket);
                $smtp_connected = true;
                $test_results[] = [
                    'name' => 'Connexion SMTP de base',
                    'status' => true,
                    'value' => 'Port 587 accessible'
                ];
            } else {
                $test_results[] = [
                    'name' => 'Connexion SMTP de base',
                    'status' => false,
                    'value' => "Erreur: $errstr ($errno)"
                ];
            }
        } catch (Exception $e) {
            $test_results[] = [
                'name' => 'Connexion SMTP de base',
                'status' => false,
                'value' => 'Exception: ' . $e->getMessage()
            ];
        }

        // Test des extensions PHP
        $openssl_loaded = extension_loaded('openssl');
        $test_results[] = [
            'name' => 'Extension OpenSSL',
            'status' => $openssl_loaded,
            'value' => $openssl_loaded ? 'Chargée' : 'Non chargée'
        ];

        // Test de la configuration
        $config_ok = defined('MAIL_SMTP_PASSWORD') && MAIL_SMTP_PASSWORD !== 'your_app_password_here';
        $test_results[] = [
            'name' => 'Mot de passe Gmail configuré',
            'status' => $config_ok,
            'value' => $config_ok ? 'Oui' : 'Non (à configurer)'
        ];

        // Test d'envoi si formulaire soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
            $test_email = $_POST['test_email'];
            $test_subject = $_POST['test_subject'] ?? 'Test SMTP - Clinique Marion';
            $test_message = $_POST['test_message'] ?? 'Ceci est un test d\'envoi SMTP.';

            $html_body = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #667eea; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Test SMTP Gmail</h2>
        </div>
        <div class='content'>
            <p><strong>Test effectué le:</strong> " . date('d/m/Y H:i:s') . "</p>
            <p><strong>Message de test:</strong></p>
            <p>" . htmlspecialchars($test_message) . "</p>
            <br>
            <p>Si vous recevez cet email, la configuration SMTP Gmail fonctionne correctement! ✅</p>
        </div>
    </div>
</body>
</html>";

            $send_result = sendEmail($test_email, 'Testeur SMTP', $test_subject, $html_body, true);

            $test_results[] = [
                'name' => 'Envoi d\'email de test',
                'status' => $send_result,
                'value' => $send_result ? 'Email envoyé à ' . htmlspecialchars($test_email) : 'Échec d\'envoi'
            ];
        }

        // Afficher les résultats
        ?>

        <div class="test-section">
            <h2>📋 Résultats des tests</h2>

            <?php foreach ($test_results as $test): ?>
            <div class="test-item">
                <div class="status <?php echo $test['status'] ? 'success' : 'error'; ?>">
                    <?php echo $test['status'] ? '✓' : '✗'; ?>
                </div>
                <div class="label"><?php echo htmlspecialchars($test['name']); ?></div>
                <div>
                    <span class="value"><?php echo htmlspecialchars($test['value']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (isset($send_result)): ?>
        <div class="message-box <?php echo $send_result ? 'success-box' : 'error-box'; ?>">
            <?php if ($send_result): ?>
            ✅ <strong>Test réussi!</strong> Email envoyé à <?php echo htmlspecialchars($test_email); ?>. Vérifiez votre boîte de réception (et le dossier spam).
            <?php else: ?>
            ❌ <strong>Test échoué!</strong> L'envoi d'email a échoué. Vérifiez les erreurs ci-dessus.
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="test-section">
            <h2>📧 Test d'envoi d'email</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="test_email">Email de destination (pour recevoir le test):</label>
                    <input type="email" id="test_email" name="test_email" placeholder="votre.email@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="test_subject">Sujet du test:</label>
                    <input type="text" id="test_subject" name="test_subject" value="Test SMTP - Clinique Marion" required>
                </div>

                <div class="form-group">
                    <label for="test_message">Message de test:</label>
                    <textarea id="test_message" name="test_message" placeholder="Votre message de test...">Ceci est un test d'envoi SMTP via Gmail. Si vous recevez cet email, tout fonctionne correctement!</textarea>
                </div>

                <button type="submit" <?php echo !$config_ok ? 'disabled' : ''; ?>>
                    📧 Envoyer un email de test
                </button>

                <?php if (!$config_ok): ?>
                <p style="color: red; margin-top: 10px;">⚠️ Configurez d'abord le mot de passe Gmail dans mail_config.php</p>
                <?php endif; ?>
            </form>
        </div>

        <div class="test-section">
            <h2>🔧 Configuration actuelle</h2>
            <div class="test-item">
                <span class="label">Serveur SMTP:</span>
                <span class="value"><?php echo MAIL_SMTP_HOST; ?>:<?php echo MAIL_SMTP_PORT; ?></span>
            </div>
            <div class="test-item">
                <span class="label">Email expéditeur:</span>
                <span class="value"><?php echo MAIL_FROM_EMAIL; ?></span>
            </div>
            <div class="test-item">
                <span class="label">Mot de passe configuré:</span>
                <span class="value"><?php echo $config_ok ? 'Oui' : 'Non'; ?></span>
            </div>
        </div>

        <div class="test-section">
            <h2>🚀 Prochaines étapes</h2>
            <div class="test-item">
                <span class="label">
                    <a href="contact.html" class="link">Tester le formulaire</a>
                </span>
                <span class="value">Formulaire de contact complet</span>
            </div>
            <div class="test-item">
                <span class="label">
                    <a href="test_email.php" class="link">Test email complet</a>
                </span>
                <span class="value">Test complet des emails</span>
            </div>
        </div>

        <div class="message-box" style="background-color: #fff3cd; border: 1px solid #ffc107; color: #856404;">
            <strong>💡 Rappel:</strong> Pour que les emails fonctionnent, vous devez:
            <ol>
                <li>Activer l'authentification à 2 facteurs sur votre compte Gmail</li>
                <li>Générer un "mot de passe d'application" dans les paramètres de sécurité</li>
                <li>Le coller dans <code>MAIL_SMTP_PASSWORD</code> dans <code>mail_config.php</code></li>
            </ol>
        </div>
    </div>
</body>
</html>