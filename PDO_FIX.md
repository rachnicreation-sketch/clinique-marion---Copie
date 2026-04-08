# 🔧 Correction Erreur PDO - Clinique Marion

## ❌ Problème identifié

L'erreur `Undefined constant PDO::ATTR_ERR_MODE` indique que l'extension PDO n'était pas activée dans PHP.

## ✅ Solution appliquée

J'ai ajouté l'extension PDO de base dans le fichier `php.ini` de WAMP (version 8.2.18).

**Modification effectuée:**
```ini
extension=pdo  # ← AJOUTÉ
extension=pdo_mysql
```

## 🚀 Actions requises

### 1. Redémarrer Apache
**Via l'interface WAMP:**
1. Cliquez sur l'icône WAMP dans la barre des tâches (icône verte)
2. Cliquez sur "Restart All Services"
3. Attendez que tous les services soient verts

### 2. Tester la correction
Accédez à: `http://localhost/clinique-marion%20-%20Copie/test_pdo.php`

Vous devriez voir:
- ✅ Extension PDO chargée
- ✅ Extension PDO MySQL chargée  
- ✅ Constantes PDO disponibles

### 3. Tester le formulaire
Une fois Apache redémarré, testez le formulaire de contact:
`http://localhost/clinique-marion%20-%20Copie/contact.html`

## 📋 Diagnostic

**Fichier modifié:** `c:\wamp64\bin\php\php8.2.18\php.ini`
**Ligne ajoutée:** `extension=pdo` (au-dessus de `extension=pdo_mysql`)

**Extensions PHP maintenant activées:**
- ✅ `extension=pdo` (base)
- ✅ `extension=pdo_mysql` (MySQL)

---

**Date:** 2024  
**Statut:** ✅ Configuration appliquée - Redémarrage Apache requis
