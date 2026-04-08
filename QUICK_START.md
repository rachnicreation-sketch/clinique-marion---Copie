# 📧 Configuration Email - Clinique Marion

## ✅ Statut: Configuration COMPLÈTE

Le système d'envoi d'emails vers **rachnicreation@gmail.com** a été configuré.

---

## 🚀 Étapes d'activation rapides

### 1️⃣ Obtenir le mot de passe d'application Gmail

1. Allez sur: https://myaccount.google.com/apppasswords
2. Sélectionnez "Mail" et "Autres (Windows, Mac, Linux)"
3. Générez le mot de passe (16 caractères)
4. Copiez-le

### 2️⃣ Mettre à jour la configuration

Ouvrez: `includes/mail_config.php`

Cherchez la ligne 12:
```php
define('MAIL_SMTP_PASSWORD', 'your_app_password_here');
```

Remplacez par:
```php
define('MAIL_SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Votre mot de passe d'application
```

### 3️⃣ Testez!

Accédez à: `http://localhost/clinique-marion%20-%20Copie/test_email.php`

---

## 📝 Fichiers modifiés/créés

| Fichier | Description |
|---------|-------------|
| `includes/mail_config.php` | ✨ NOUVEAU - Configuration complète SMTP + classe SimpleSMTPMailer |
| `process_form.php` | 🔄 MODIFIÉ - Ajout envoi d'emails |
| `test_email.php` | ✨ NOUVEAU - Interface de test |
| `EMAIL_SETUP.md` | ✨ NOUVEAU - Guide complet de configuration |
| `QUICK_START.md` | ✨ NOUVEAU - Ce fichier |

---

## 📧 Fonctionnalités

### Envoi automatique sur formulaire de contact:

✅ **Email de confirmation** → Envoyé au client  
Email de notification → Envoyé à rachnicreation@gmail.com

**Détails du client**:
- Sujet: "Confirmation de votre message - Clinique Marion"
- Contenu: Résumé avec accusé de réception

**Notification admin**:
- Sujet: "Nouveau message de contact"
- Destinataire: rachnicreation@gmail.com
- Contenu: Détails complets pour traitement

---

## 🔧 Configuration SMTP

| Paramètre | Valeur |
|-----------|--------|
| **Host** | smtp.gmail.com |
| **Port** | 587 |
| **Authentification** | AUTH LOGIN |
| **Chiffrement** | STARTTLS |
| **Email** | rachnicreation@gmail.com |
| **Classe** | SimpleSMTPMailer (PHP natif - pas de dépendances) |

---

## ✨ Points forts

✅ **Sans dépendances externes** - Implémentation SMTP pure PHP  
✅ **Sécurisé** - Utilise TLS et authentification  
✅ **HTML configurable** - Templates HTML personnalisables  
✅ **Logs des erreurs** - Gestion complète des exceptions  
✅ **Interface de test** - Vérification facile de la configuration  

---

## 🐛 Dépannage

### Les emails ne s'envoient pas?

1. **Vérifiez le mot de passe**  
   `includes/mail_config.php` ligne 12

2. **Consultez les logs PHP**  
   `c:\wamp64\logs\php_error.log`

3. **Vérifiez OpenSSL dans PHP**  
   Modifiez `c:\wamp64\bin\php\php*.*.*/php.ini`:
   - Cherchez: `;extension=php_openssl.dll`
   - Enlevez le `;` au début: `extension=php_openssl.dll`
   - Redémarrez Apache

4. **Utilisez le test**  
   Allez sur `test_email.php` pour diagnostiquer les problèmes

---

## 📞 Support

Pour les questions concernant l'intégration Gmail:
- [Aide Google: Mots de passe des applications](https://support.google.com/accounts/answer/185833)
- [SPF et DKIM pour Gmail](https://support.google.com/a/answer/81126)

---

**Configuration créée:** 2024  
**Version:** 1.0  
**État:** ✅ Prêt à l'emploi
