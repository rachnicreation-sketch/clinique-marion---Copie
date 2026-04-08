<?php
$host = 'localhost';
$db   = 'clinique_marion_copie';
$user = 'root';
$pass = ''; // Default WAMP password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Utiliser les valeurs numériques si les constantes ne sont pas définies
$options = [
    (defined('PDO::ATTR_ERR_MODE') ? PDO::ATTR_ERR_MODE : 3) => (defined('PDO::ERRMODE_EXCEPTION') ? PDO::ERRMODE_EXCEPTION : 2),
    (defined('PDO::ATTR_DEFAULT_FETCH_MODE') ? PDO::ATTR_DEFAULT_FETCH_MODE : 19) => (defined('PDO::FETCH_ASSOC') ? PDO::FETCH_ASSOC : 2),
    (defined('PDO::ATTR_EMULATE_PREPARES') ? PDO::ATTR_EMULATE_PREPARES : 20) => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For production, log error and show generic message
     // throw new \PDOException($e->getMessage(), (int)$e->getCode());
     die(json_encode(['status' => 'error', 'message' => 'Erreur de connexion à la base de données.']));
}
?>
