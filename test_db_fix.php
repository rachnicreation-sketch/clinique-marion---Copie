<?php
/**
 * Test de connexion à la base de données avec solution de contournement PDO
 * Accès: http://localhost/clinique-marion%20-%20Copie/test_db_fix.php
 */

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Connexion DB Fix - Clinique Marion</title>
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
            max-width: 600px;
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
        .link {
            color: #667eea;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Test Connexion DB (Fix)</h1>
        <p class="subtitle">Test avec solution de contournement PDO</p>

        <?php
        $status_overall = true;
        $tests = [];

        // Test 1: Extensions PHP
        $pdo_loaded = extension_loaded('pdo');
        $pdo_mysql_loaded = extension_loaded('pdo_mysql');

        $tests[] = [
            'name' => 'Extension PDO',
            'status' => $pdo_loaded,
            'value' => $pdo_loaded ? 'Chargée' : 'Non chargée'
        ];

        $tests[] = [
            'name' => 'Extension PDO MySQL',
            'status' => $pdo_mysql_loaded,
            'value' => $pdo_mysql_loaded ? 'Chargée' : 'Non chargée'
        ];

        // Test 2: Constantes PDO
        $constants_available = defined('PDO::ATTR_ERR_MODE');
        $tests[] = [
            'name' => 'Constantes PDO',
            'status' => $constants_available,
            'value' => $constants_available ? 'Disponibles' : 'Indisponibles (utilise valeurs numériques)'
        ];

        // Test 3: Connexion DB
        $db_connected = false;
        $connection_error = '';
        $table_count = 0;

        try {
            require_once 'includes/db_config.php';

            if ($pdo) {
                $db_connected = true;

                // Compter les tables
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = 'clinique_marion_copie'");
                $result = $stmt->fetch();
                $table_count = $result['count'] ?? 0;

                // Tester une requête simple
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
                $result = $stmt->fetch();
                $message_count = $result['count'] ?? 0;
            }
        } catch (Exception $e) {
            $db_connected = false;
            $connection_error = $e->getMessage();
            $status_overall = false;
        }

        $tests[] = [
            'name' => 'Connexion base de données',
            'status' => $db_connected,
            'value' => $db_connected ? 'Réussie' : 'Échouée',
            'error' => $connection_error
        ];

        if ($db_connected) {
            $tests[] = [
                'name' => 'Tables dans la base',
                'status' => true,
                'value' => $table_count . ' table(s)'
            ];

            $tests[] = [
                'name' => 'Messages existants',
                'status' => true,
                'value' => $message_count . ' message(s)'
            ];
        }

        // Afficher les résultats
        ?>

        <div class="test-section">
            <h2>📋 Résultats des tests</h2>

            <?php foreach ($tests as $test): ?>
            <div class="test-item">
                <div class="status <?php echo $test['status'] ? 'success' : 'error'; ?>">
                    <?php echo $test['status'] ? '✓' : '✗'; ?>
                </div>
                <div class="label"><?php echo htmlspecialchars($test['name']); ?></div>
                <div>
                    <?php if (isset($test['value'])): ?>
                        <span class="value"><?php echo htmlspecialchars($test['value']); ?></span>
                    <?php elseif (isset($test['error'])): ?>
                        <span class="value" style="color: red;"><?php echo htmlspecialchars($test['error']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($status_overall): ?>
        <div class="message-box success-box">
            ✅ <strong>Succès!</strong> La connexion à la base de données fonctionne avec la solution de contournement.
        </div>
        <?php else: ?>
        <div class="message-box error-box">
            ❌ <strong>Erreur!</strong> La connexion à la base de données échoue encore.
        </div>
        <?php endif; ?>

        <div class="test-section">
            <h2>🔧 Solution appliquée</h2>
            <p>Le code utilise maintenant des valeurs numériques au lieu des constantes PDO si elles ne sont pas disponibles:</p>
            <div class="code-block">
// Avant (provoquait l'erreur):
PDO::ATTR_ERR_MODE => PDO::ERRMODE_EXCEPTION

// Après (solution de contournement):
(defined('PDO::ATTR_ERR_MODE') ? PDO::ATTR_ERR_MODE : 3) => (defined('PDO::ERRMODE_EXCEPTION') ? PDO::ERRMODE_EXCEPTION : 2)
            </div>
        </div>

        <div class="test-section">
            <h2>🚀 Prochaines étapes</h2>
            <div class="test-item">
                <span class="label">
                    <a href="contact.html" class="link">Tester le formulaire</a>
                </span>
                <span class="value">Formulaire de contact</span>
            </div>
            <div class="test-item">
                <span class="label">
                    <a href="test_email.php" class="link">Tester les emails</a>
                </span>
                <span class="value">Envoi d'emails</span>
            </div>
            <div class="test-item">
                <span class="label">
                    <a href="test_db.php" class="link">Test complet DB</a>
                </span>
                <span class="value">Test complet base de données</span>
            </div>
        </div>

        <div class="message-box" style="background-color: #fff3cd; border: 1px solid #ffc107; color: #856404;">
            <strong>💡 Conseil:</strong> Pour une solution permanente, redémarrez Apache via l'icône WAMP pour activer l'extension PDO correctement.
        </div>
    </div>
</body>
</html>