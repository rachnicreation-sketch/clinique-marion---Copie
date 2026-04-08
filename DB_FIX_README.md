# 🔧 Solution de Contournement PDO - Clinique Marion

## ❌ Problème initial

L'erreur `Undefined constant PDO::ATTR_ERR_MODE` empêchait le fonctionnement du formulaire de contact.

**Cause:** Extension PDO de base non activée dans PHP malgré la modification du `php.ini`.

## ✅ Solution appliquée

Modification du fichier `includes/db_config.php` pour utiliser des valeurs numériques au lieu des constantes PDO quand elles ne sont pas disponibles.

### Code modifié:

**Avant (provoquait l'erreur):**
```php
$options = [
    PDO::ATTR_ERR_MODE            => PDO::ERR_MODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
```

**Après (solution de contournement):**
```php
$options = [
    (defined('PDO::ATTR_ERR_MODE') ? PDO::ATTR_ERR_MODE : 3) => (defined('PDO::ERRMODE_EXCEPTION') ? PDO::ERRMODE_EXCEPTION : 2),
    (defined('PDO::ATTR_DEFAULT_FETCH_MODE') ? PDO::ATTR_DEFAULT_FETCH_MODE : 19) => (defined('PDO::FETCH_ASSOC') ? PDO::FETCH_ASSOC : 2),
    (defined('PDO::ATTR_EMULATE_PREPARES') ? PDO::ATTR_EMULATE_PREPARES : 20) => false,
];
```

## 📊 Valeurs numériques utilisées:

| Constante | Valeur numérique | Description |
|-----------|------------------|-------------|
| `PDO::ATTR_ERR_MODE` | `3` | Mode d'erreur |
| `PDO::ERRMODE_EXCEPTION` | `2` | Mode exception |
| `PDO::ATTR_DEFAULT_FETCH_MODE` | `19` | Mode de récupération par défaut |
| `PDO::FETCH_ASSOC` | `2` | Récupération associative |
| `PDO::ATTR_EMULATE_PREPARES` | `20` | Émulation des préparations |

## 🧪 Tester la solution

Accédez à: `http://localhost/clinique-marion%20-%20Copie/test_db_fix.php`

Vous devriez voir:
- ✅ Extension PDO (même si non chargée)
- ✅ Connexion base de données réussie
- ✅ Tables accessibles

## 📧 Tester le formulaire

Le formulaire de contact devrait maintenant fonctionner:
`http://localhost/clinique-marion%20-%20Copie/contact.html`

## 🔄 Solution permanente

Pour une solution définitive, redémarrez Apache via l'icône WAMP pour activer correctement l'extension PDO.

---

**Date:** 2024  
**Statut:** ✅ Solution de contournement appliquée  
**Fichier modifié:** `includes/db_config.php`
