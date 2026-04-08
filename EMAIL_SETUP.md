# Configuration de l'envoi d'emails - Clinique Marion

## Configuration Gmail avec App Passwords

L'application est maintenant configurée pour envoyer des emails via **Gmail (rachnicreation@gmail.com)**.

### Étapes de configuration:

#### 1. Créer un mot de passe d'application Gmail

Gmail nécessite un "App Password" pour une sécurité renforcée. Suivez ces étapes:

1. Allez sur votre compte Google: https://myaccount.google.com/
2. Cliquez sur **"Sécurité"** dans le menu gauche
3. Activez l'authentification à deux facteurs (2FA) si ce n'est pas déjà fait
4. Retournez à la section **Sécurité**
5. Cherchez **"Mots de passe des applications"** (ou "App passwords")
6. Sélectionnez:
   - **Application**: Mail
   - **Appareil**: Autres (Windows, Mac, Linux)
7. Cliquez sur **Générer**
8. Gmail affichera un mot de passe à 16 caractères
9. **Copiez ce mot de passe**

#### 2. Mettre à jour la configuration PHP

Ouvrez le fichier `includes/mail_config.php` et remplacez:

```php
define('MAIL_SMTP_PASSWORD', 'your_app_password_here');
```

Par:

```php
define('MAIL_SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Remplacez par votre mot de passe d'application
```

**Note**: Le mot de passe contient des espaces, gardez-les tel quel.

#### 3. Tester l'envoi d'emails

Le système enverra automatiquement:
- **Email de confirmation au client** quand il soumet le formulaire de contact
- **Email de notification à l'admin** (rachnicreation@gmail.com) avec les détails du message

### Structure des emails

#### Email de confirmation (client):
- Sujet: "Confirmation de votre message - Clinique Marion"
- Contenu: Résumé du message envoyé avec confirmation de réception

#### Email de notification (admin):
- Sujet: "Nouveau message de contact"
- Destinataire: rachnicreation@gmail.com
- Contenu: Détails complets du message pour traitement

### Fichiers modifiés:

1. **includes/mail_config.php** - Configuration SMTP et classe SimpleSMTPMailer
2. **process_form.php** - Intégration de l'envoi d'emails

### Dépannage:

**Problème**: Les emails ne sont pas envoyés
- Vérifiez le mot de passe d'application dans `mail_config.php`
- Vérifiez les logs PHP: `c:\wamp64\logs\php_error.log`
- Assurez-vous que PHP a accès à OpenSSL (pour SSL/TLS)

**Problème**: Erreur "SSL/TLS"
- Vérifiez que OpenSSL est activé dans PHP
- Modifiez `php.ini`: `extension=php_openssl.dll`

### Support SSL/TLS

La classe SimpleSMTPMailer utilise:
- Port: 587 (STARTTLS)
- Chiffrement: TLS
- Authentification: AUTH LOGIN

Ces paramètres sont optimisés pour Gmail et n'ont pas besoin d'ajustements.

---

**Configuration créée le**: 2024
**Dernier mise à jour**: 2024
