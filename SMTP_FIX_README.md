# 🔧 Correction SMTP Gmail - Clinique Marion

## ❌ Problème identifié

L'erreur `SSL operation failed with code 1. OpenSSL Error messages: error:0A00010B:SSL routines::wrong version number` indiquait un problème de protocole SSL.

**Cause:** Le code utilisait `ssl://smtp.gmail.com:587` (SSL direct), mais Gmail utilise STARTTLS sur le port 587.

## ✅ Solution appliquée

Modification de la classe `SimpleSMTPMailer` pour gérer correctement les connexions Gmail:

### Logique de connexion corrigée:

**Port 587 (Gmail recommandé):**
1. Connexion normale: `fsockopen('smtp.gmail.com', 587)`
2. Commande `STARTTLS` pour activer le chiffrement
3. Authentification SMTP

**Port 465 (SSL direct):**
1. Connexion SSL directe: `fsockopen('ssl://smtp.gmail.com', 465)`
2. Authentification SMTP immédiate

## 📊 Configuration Gmail

| Paramètre | Valeur | Description |
|-----------|--------|-------------|
| **Host** | smtp.gmail.com | Serveur SMTP Gmail |
| **Port** | 587 | Port STARTTLS recommandé |
| **Sécurité** | STARTTLS | Chiffrement après connexion |
| **Authentification** | LOGIN | Méthode d'authentification |

## 🧪 Tester la correction

**Test de connexion SMTP:**
```
http://localhost/clinique-marion%20-%20Copie/test_smtp.php
```

**Test d'envoi d'email:**
- Entrez votre email de test
- Cliquez sur "Envoyer un email de test"
- Vérifiez que vous recevez l'email

## 📧 Tester le formulaire

Le formulaire de contact devrait maintenant envoyer des emails correctement:
```
http://localhost/clinique-marion%20-%20Copie/contact.html
```

## 🔐 Configuration requise

Pour que les emails fonctionnent, configurez le mot de passe d'application Gmail:

1. **Activer 2FA** sur votre compte Gmail
2. **Générer un mot de passe d'application:**
   - Allez sur: https://myaccount.google.com/apppasswords
   - Sélectionnez "Mail" et "Ordinateur"
   - Copiez le mot de passe généré (16 caractères)
3. **Mettre à jour** `includes/mail_config.php`:
   ```php
   define('MAIL_SMTP_PASSWORD', 'xxxx xxxx xxxx xxxx'); // Votre mot de passe d'application
   ```

## 📋 Fichiers modifiés

- `includes/mail_config.php` - Correction de la logique SMTP
- `test_smtp.php` - Nouveau script de test SMTP

---

**Date:** 2024  
**Statut:** ✅ Problème SSL résolu  
**Test:** `test_smtp.php`
