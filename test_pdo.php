<?php
// Test rapide de PDO
echo "<h1>Test PDO</h1>";

if (extension_loaded('pdo')) {
    echo "<p style='color: green;'>✅ Extension PDO chargée</p>";
} else {
    echo "<p style='color: red;'>❌ Extension PDO NON chargée</p>";
}

if (extension_loaded('pdo_mysql')) {
    echo "<p style='color: green;'>✅ Extension PDO MySQL chargée</p>";
} else {
    echo "<p style='color: red;'>❌ Extension PDO MySQL NON chargée</p>";
}

// Test des constantes PDO
try {
    $test = PDO::ATTR_ERR_MODE;
    echo "<p style='color: green;'>✅ Constantes PDO disponibles</p>";
} catch (Error $e) {
    echo "<p style='color: red;'>❌ Constantes PDO indisponibles: " . $e->getMessage() . "</p>";
}

echo "<p><strong>Si les extensions sont chargées mais que vous avez encore des erreurs, redémarrez Apache via l'icône WAMP dans la barre des tâches.</strong></p>";
?>