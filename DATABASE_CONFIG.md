# 🗄️ Configuration Base de Données - Clinique Marion

## ✅ Statut: Configuration COMPLÈTE

La base de données **clinique_marion** a été créée et configurée avec succès dans WAMP.

---

## 📊 Informations de la Base de Données

| Paramètre | Valeur |
|-----------|--------|
| **Database** | clinique_marion |
| **Host** | localhost |
| **User** | root |
| **Password** | (vide - défaut WAMP) |
| **Port** | 3306 (défaut MySQL) |
| **Version MySQL** | 8.3.0 |

---

## 📋 Tables créées

### Table `messages` (pour les formulaires de contact)

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | INT (auto-increment) | Identifiant unique |
| `nom` | VARCHAR(255) | Nom du contact |
| `email` | VARCHAR(255) | Email du contact |
| `telephone` | VARCHAR(50) | Téléphone (optionnel) |
| `message` | TEXT | Contenu du message |
| `date_envoi` | TIMESTAMP | Date/heure d'envoi (auto) |

### Autres tables disponibles

La base de données contient également:
- `access_requests` - Demandes d'accès
- `analyses_labo` - Analyses de laboratoire
- `audit_logs` - Logs d'audit
- `caisse_transactions` - Transactions de caisse
- `chambres` - Chambres
- `constantes_vitales` - Constantes vitales
- `consultations` - Consultations
- `factures` - Factures
- `hospitalisations` - Hospitalisations
- `laboratoires` - Laboratoires
- `lits` - Lits
- `medicaments` - Médicaments
- `notes_infirmieres` - Notes infirmières
- `ordonnances` - Ordonnances
- `patients` - Patients
- `planning_gardes` - Planning des gardes
- `prescriptions_labo` - Prescriptions de labo
- `rendez_vous` - Rendez-vous
- `resultats_labo` - Résultats de labo
- `users` - Utilisateurs

---

## ⚙️ Configuration PHP

Fichier: `includes/db_config.php`

```php
$host = 'localhost';
$db   = 'clinique_marion';
$user = 'root';
$pass = ''; // Mot de passe vide (défaut WAMP)
$charset = 'utf8mb4';
```

Cette configuration est **automatiquement utilisée** par:
- `process_form.php` - Traitement des formulaires de contact
- Tous les scripts PHP qui nécessitent une base de données

---

## 🔌 Accès à PhpMyAdmin

Pour gérer la base de données via interface web:

1. Assurez-vous que WAMP est running
2. Allez sur: **http://localhost/phpmyadmin/**
3. Username: `root`
4. Password: (laisser vide)
5. Cliquez sur **Go**
6. Allez dans **clinique_marion** pour voir toutes les tables

---

## 🧪 Tester la connexion

Créez un fichier `test_db.php` dans la racine du projet:

```php
<?php
require_once 'includes/db_config.php';

try {
    // Test la connexion
    $stmt = $pdo->query("SELECT COUNT(*) FROM messages");
    $count = $stmt->fetchColumn();
    echo "✅ Connexion réussie! Nombre de messages: " . $count;
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
```

Accédez à: `http://localhost/clinique-marion%20-%20Copie/test_db.php`

---

## 📝 Ajouter se données d'exemple

Pour tester, vous pouvez insérer des messages d'exemple:

```sql
INSERT INTO messages (nom, email, telephone, message) VALUES
('Jean Dupont', 'jean@example.com', '0123456789', 'Demande de rendez-vous'),
('Marie Martin', 'marie@example.com', '0987654321', 'Consultation urgente');
```

Via PhpMyAdmin:
1. Allez dans l'onglet **SQL**
2. Collez la requête
3. Cliquez sur **Exécuter**

---

## 🔐 Sécurité

⚠️ **IMPORTANT pour la production:**

1. **Changez le mot de passe MySQL** - Le mot de passe vide n'est pas sûr en production
2. **Créez un utilisateur MySQL dédié** - Au lieu d'utiliser `root`
3. **Limitations des privilèges** - L'utilisateur ne doit avoir accès qu'à `clinique_marion`
4. **Utilisez HTTPS** - Pour toutes les données sensibles
5. **Validez tous les inputs** - Pour éviter les injections SQL

---

## 📂 Fichiers de configuration

| Fichier | Description |
|---------|-------------|
| `database.sql` | Script SQL original (lecture seule) |
| `includes/db_config.php` | Configuration PHP (PDO) |
| `includes/mail_config.php` | Configuration email |
| `process_form.php` | Traitement des formulaires |

---

## 🚀 Prochaines étapes

✅ Base de données configurée  
✅ Connexion PDO fonctionnelle  
✅ Table `messages` prête à recevoir les données  
⏳ Ajouter des utilisateurs MySQL pour la production  
⏳ Implémenter les règles de sécurité  

---

**Configuration créée:** 2024  
**Dernière mise à jour:** 2024  
**Version:** 1.0
