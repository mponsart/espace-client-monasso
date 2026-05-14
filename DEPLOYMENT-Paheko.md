# 🚀 Déploiement sur Paheko

## 📁 Structure à envoyer via FTP

Paheko utilise généralement `/www/` ou `/httpdocs/` comme racine web.

### Connexion FTP Paheko
- **Hôte FTP** : `ftp.monasso.eu` (ou ton nom de domaine)
- **Identifiant** : Ton identifiant Paheko (ex: `monasso`)
- **Mot de passe** : Celui défini dans l'espace client Paheko
- **Port** : 21 (ou 22 pour SFTP)

### Arborescence recommandée

```
/home/monasso/               ← Racine de ton compte Paheko
├── www/                     ← RACINE WEB (accessible publiquement)
│   ├── index.php
│   ├── .htaccess
│   └── stripe-webhook.php
│
├── src/                     ← HORS RACINE (sécurisé)
├── templates/               ← HORS RACINE
├── config/                  ← HORS RACINE
├── migrations/              ← HORS RACINE
├── .env                     ← HORS RACINE (sensible)
└── logs/                    ← Pour les logs
```

---

## 📤 Étapes de déploiement

### 1. Créer la base de données

Dans ton **espace client Paheko** → **Bases de données** :

1. Crée une nouvelle base (ex: `monasso_db`)
2. Note les informations :
   - **Host** : `localhost` (ou `mysql51-prm.your-server.de`)
   - **Database** : `monasso_db`
   - **Username** : `monasso_db`
   - **Password** : Celui que tu as défini
3. Ouvre **phpMyAdmin** et importe les migrations :
   ```
   /home/monasso/migrations/001_create_users_table.sql
   /home/monasso/migrations/002_create_subscriptions_table.sql
   /home/monasso/migrations/003_create_payments_table.sql
   /home/monasso/migrations/004_create_notifications_table.sql
   /home/monasso/migrations/005_create_rgpd_consents_table.sql
   ```

---

### 2. Transférer les fichiers via FTP

**Avec FileZilla** :

1. **Site** : `ftp.monasso.eu`
2. **Identifiant** : `monasso`
3. **Mot de passe** : `********`
4. **Port** : `21`

**Transferts** :
```
✅ public/*          → /home/monasso/www/
✅ src/              → /home/monasso/src/
✅ templates/        → /home/monasso/templates/
✅ config/           → /home/monasso/config/
✅ migrations/       → /home/monasso/migrations/
✅ .env              → /home/monasso/.env
```

---

### 3. Configurer le fichier .env

Crée `/home/monasso/.env` avec tes valeurs Paheko :

```env
# Application
APP_NAME=MonAsso
APP_URL=https://monasso.eu
APP_ENV=production
APP_DEBUG=false
SUPPORT_EMAIL=support@monasso.eu

# Base de données (valeurs Paheko)
DB_HOST=localhost
DB_DATABASE=monasso_db
DB_USERNAME=monasso_db
DB_PASSWORD=ton_mot_de_passe_bdd
DB_CHARSET=utf8mb4

# Stripe
STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# cPanel (si utilisé)
CPANEL_HOST=https://ton-serveur-cpanel:2083
CPANEL_USERNAME=monasso
CPANEL_TOKEN=ton_token

# Sécurité
APP_KEY=ta_clé_secrète_32_caractères
SESSION_LIFETIME=7200
RATE_LIMIT_MAX_ATTEMPTS=5
RATE_LIMIT_WINDOW=300

# Emails (optionnel - Paheko supporte mail())
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ton_email@gmail.com
MAIL_PASSWORD=ton_app_password
MAIL_FROM_ADDRESS=noreply@monasso.eu
MAIL_FROM_NAME=MonAsso

# Logs
LOG_LEVEL=error
LOG_PATH=/home/monasso/logs/
```

---

### 4. Permissions des fichiers

Via **SSH** (si accès disponible) ou **FileZilla** (clic droit → Permissions) :

```bash
# Dossiers
chmod 755 /home/monasso/src
chmod 755 /home/monasso/templates
chmod 755 /home/monasso/config
chmod 755 /home/monasso/logs
chmod 755 /home/monasso/www

# Fichiers
chmod 644 /home/monasso/www/*.php
chmod 644 /home/monasso/www/.htaccess
chmod 644 /home/monasso/config/*.php
chmod 600 /home/monasso/.env
```

---

### 5. Configurer le webhook Stripe

1. Va sur [Stripe Dashboard → Webhooks](https://dashboard.stripe.com/webhooks)
2. **Ajouter un endpoint** :
   ```
   URL : https://monasso.eu/stripe-webhook.php
   ```
3. **Événements à écouter** :
   - ✅ `invoice.payment_succeeded`
   - ✅ `invoice.payment_failed`
   - ✅ `customer.subscription.deleted`
4. Copie le **Signing Secret** dans `.env` :
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxx
   ```

---

### 6. Tester l'installation

1. Ouvre `https://monasso.eu`
2. Vérifie que la page d'accueil s'affiche
3. Crée un compte test
4. Active le **mode test Stripe** avec une carte test :
   - `4242 4242 4242 4242` (expiration/futur, CVC quelconque)
5. Vérifie que :
   - ✅ Le paiement fonctionne
   - ✅ Le webhook est reçu (`/logs/` ou Dashboard Stripe)
   - ✅ L'email de bienvenue est envoyé
   - ✅ Le dashboard affiche l'abonnement actif

---

## 🛡️ Spécificités Paheko

### PHP
- **Version** : Sélectionne PHP 8.x dans l'espace client Paheko
- **php.ini** : Modifiable via l'espace client
- **display_errors** : `Off` en production
- **log_errors** : `On`

### Emails
- Paheko supporte `mail()` nativement
- Pour une meilleure délivrabilité, utilise **SMTP externe** (Gmail, Sendinblue, etc.)

### Logs
- Crée le dossier : `/home/monasso/logs/`
- Permissions : `chmod 755`
- Consultable via FTP ou SSH

### HTTPS/SSL
- Paheko fournit un certificat **Let's Encrypt gratuit**
- Active-le dans l'espace client → **SSL/TLS**
- Redirection HTTP → HTTPS automatique

---

## 🔧 Fichier .htaccess (déjà inclus)

```apache
# /home/monasso/www/.htaccess
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Sécurité
<FilesMatch "\.(env|log|sql|md)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

## 🆘 Dépannage

### Erreur 500
- Vérifie les logs : `/home/monasso/logs/error.log`
- Active le debug : `APP_DEBUG=true` dans `.env`
- Vérifie les permissions : `chmod 644` pour les `.php`

### Erreur de connexion BDD
- Vérifie les identifiants dans `.env`
- Teste via phpMyAdmin
- Vérifie que l'utilisateur a les droits sur la base

### Webhook non reçu
- Vérifie que `stripe-webhook.php` est dans `/www/`
- Teste l'URL : `https://monasso.eu/stripe-webhook.php` (doit retourner 400 sans signature)
- Vérifie les logs Stripe Dashboard

### Emails non envoyés
- Utilise un SMTP externe (Gmail, Sendinblue)
- Vérifie les logs serveur Paheko

---

## ✅ Checklist de déploiement

- [ ] Base de données créée + migrations importées
- [ ] Fichiers transférés via FTP
- [ ] `.env` configuré avec les bonnes valeurs
- [ ] Permissions définies (755/644/600)
- [ ] SSL activé (HTTPS)
- [ ] Webhook Stripe configuré
- [ ] Test de paiement effectué
- [ ] Emails fonctionnels
- [ ] Logs consultables
- [ ] `.env` exclu de Git (`.gitignore`)

---

**🎉 Déploiement Paheko terminé !**

Ta plateforme MonAsso est maintenant en ligne sur Paheko, sécurisée et automatisée.
