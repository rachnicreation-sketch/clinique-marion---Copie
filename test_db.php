<?php
/**
 * Script de test pour vérifier la connexion à la base de données
 * Accès: http://localhost/clinique-marion%20-%20Copie/test_db.php
 */

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Base de Données - Clinique Marion</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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
            max-width: 700px;
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
        .table-container {
            overflow-x: auto;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f5f5f5;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🗄️ Test de Connexion - Base de Données</h1>
        <p class="subtitle">Vérification de la configuration MySQL</p>

        <?php
        $status_overall = true;
        $tests = [];

        // Test 1: Vérifier si le fichier db_config existe
        $test_file_exists = file_exists('includes/db_config.php');
        $tests[] = [
            'name' => 'Fichier de configuration',
            'status' => $test_file_exists,
            'file' => 'includes/db_config.php'
        ];

        if (!$test_file_exists) {
            $status_overall = false;
        } else {
            // Charger la configuration
            require_once 'includes/db_config.php';

            // Test 2: Vérifier la connexion PDO
            $pdo_connected = false;
            $connection_error = '';

            try {
                // La variable $pdo est définie dans db_config.php
                if ($pdo) {
                    $pdo_connected = true;
                }
            } catch (Exception $e) {
                $pdo_connected = false;
                $connection_error = $e->getMessage();
                $status_overall = false;
            }

            $tests[] = [
                'name' => 'Connexion PDO à MySQL',
                'status' => $pdo_connected,
                'error' => $connection_error
            ];

            if ($pdo_connected) {
                // Test 3: Vérifier la base de données
                try {
                    $stmt = $pdo->query("SELECT DATABASE() AS db");
                    $result = $stmt->fetch();
                    $db_name = $result['db'] ?? 'Inconnu';
                    
                    $tests[] = [
                        'name' => 'Base de données active',
                        'status' => true,
                        'value' => $db_name
                    ];
                } catch (Exception $e) {
                    $tests[] = [
                        'name' => 'Base de données active',
                        'status' => false,
                        'error' => $e->getMessage()
                    ];
                    $status_overall = false;
                }

                // Test 4: Vérifier la table messages
                try {
                    $stmt = $pdo->query("SHOW TABLES LIKE 'messages'");
                    $table_exists = $stmt->rowCount() > 0;

                    $tests[] = [
                        'name' => 'Table "messages"',
                        'status' => $table_exists,
                        'value' => $table_exists ? 'Existe' : 'Non trouvée'
                    ];

                    if (!$table_exists) {
                        $status_overall = false;
                    }
                } catch (Exception $e) {
                    $tests[] = [
                        'name' => 'Table "messages"',
                        'status' => false,
                        'error' => $e->getMessage()
                    ];
                    $status_overall = false;
                }

                // Test 5: Compter les messages
                try {
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM messages");
                    $result = $stmt->fetch();
                    $count = $result['count'] ?? 0;

                    $tests[] = [
                        'name' => 'Messages dans la table',
                        'status' => true,
                        'value' => $count . ' message(s)'
                    ];
                } catch (Exception $e) {
                    $tests[] = [
                        'name' => 'Messages dans la table',
                        'status' => false,
                        'error' => $e->getMessage()
                    ];
                }

                // Test 6: Vérifier la structure de la table
                try {
                    $stmt = $pdo->query("DESCRIBE messages");
                    $columns = $stmt->fetchAll();

                    $expected_columns = ['id', 'nom', 'email', 'telephone', 'message', 'date_envoi'];
                    $found_columns = array_map(function($col) { return $col['Field']; }, $columns);
                    $has_all_columns = count(array_intersect($expected_columns, $found_columns)) == count($expected_columns);

                    $tests[] = [
                        'name' => 'Structure de la table',
                        'status' => $has_all_columns,
                        'value' => count($columns) . ' colonne(s)'
                    ];
                } catch (Exception $e) {
                    $tests[] = [
                        'name' => 'Structure de la table',
                        'status' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }
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
                    <?php elseif (isset($test['file'])): ?>
                        <span class="value"><?php echo htmlspecialchars($test['file']); ?></span>
                    <?php elseif (isset($test['error'])): ?>
                        <span class="value" style="color: red;"><?php echo htmlspecialchars($test['error']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($status_overall): ?>
        <div class="message-box success-box">
            ✅ <strong>Succès!</strong> La base de données est correctement configurée et fonctionnelle.
        </div>
        <?php else: ?>
        <div class="message-box error-box">
            ❌ <strong>Erreur!</strong> Certains tests ont échoué. Vérifiez votre configuration.
        </div>
        <?php endif; ?>

        <?php if (isset($columns)): ?>
        <div class="test-section">
            <h2>📊 Structure de la table "messages"</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Colonne</th>
                            <th>Type</th>
                            <th>Null</th>
                            <th>Clé</th>
                            <th>Par défaut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($columns as $col): ?>
                        <tr>
                            <td><strong><?php echo $col['Field']; ?></strong></td>
                            <td><code><?php echo $col['Type']; ?></code></td>
                            <td><?php echo $col['Null'] === 'YES' ? '✓' : ''; ?></td>
                            <td><?php echo $col['Key']; ?></td>
                            <td><code><?php echo $col['Default'] ?: '—'; ?></code></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="test-section">
            <h2>ℹ️ Informations de configuration</h2>
            <div class="test-item">
                <span class="label">Host:</span>
                <span class="value">localhost</span>
            </div>
            <div class="test-item">
                <span class="label">Base de données:</span>
                <span class="value">clinique_marion</span>
            </div>
            <div class="test-item">
                <span class="label">Utilisateur:</span>
                <span class="value">root</span>
            </div>
            <div class="test-item">
                <span class="label">Charset:</span>
                <span class="value">utf8mb4</span>
            </div>
        </div>

        <div class="test-section">
            <h2>🔗 Liens utiles</h2>
            <div class="test-item">
                <span class="label">
                    <a href="http://localhost/phpmyadmin" class="link">PhpMyAdmin</a>
                </span>
                <span class="value">Gérer la base de données</span>
            </div>
            <div class="test-item">
                <span class="label">
                    <a href="test_email.php" class="link">Test Email</a>
                </span>
                <span class="value">Tester l'envoi d'emails</span>
            </div>
            <div class="test-item">
                <span class="label">
                    <a href="contact.html" class="link">Formulaire de contact</a>
                </span>
                <span class="value">Page de contact en production</span>
            </div>
        </div>
    </div>
</body>
</html>
