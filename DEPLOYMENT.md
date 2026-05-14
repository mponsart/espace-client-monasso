# 🚀 Guide de Déploiement FTP - MonAsso

## 📋 Prérequis

- Hébergement compatible PHP 7.4+ (idéalement PHP 8.x)
- Base de données MySQL/MariaDB
- Compte FTP avec accès en écriture
- Compte Stripe (clés API + webhook secret)
- Compte cPanel avec token UAPI (optionnel, pour provisioning automatique)

---

## 📁 Structure à déployer

```
espace-client-monasso/
├── public/              → À mettre à la racine de l'hébergement
│   ├── index.php
│   ├── .htaccess
│   └── stripe-webhook.php
├── src/                 → À mettre hors racine web (sécurité)
│   ├── Controller/
│   ├── Model/
│   ├── Service/
│   ├── Auth.php
│   ├── Database.php
│   ├── Security.php
│   └── router.php
├── templates/           → À mettre hors racine web
│   ├── admin/
│   ├── auth/
│   ├── dashboard.php
│   ├── errors/
│   ├── faq/
│   └── home.php
├── config/              → À mettre hors racine web
│   ├── app.php
│   ├── database.php
│   ├── stripe.php
│   └── cpanel.php
├── migrations/          → Pour créer la BDD
│   └── *.sql
└── README.md
```

---

## 🔧 Étapes de déploiement

### 1. Transférer les fichiers via FTP

**Recommandation :** Place `public/` à la racine web (www/ ou public_html/) et le reste hors racine.

**Structure recommandée :**
```
/home/monasso/
├── www/                  → Racine web (accessible depuis le web)
│   ├── index.php
│   ├── .htaccess
│   └── stripe-webhook.php
├── src/                  → Hors racine (non accessible)
├── templates/            → Hors racine
├── config/               → Hors racine
└── migrations/           → Hors racine
```

**Commandes FTP (FileZilla, Cyberduck, etc.) :**
1. Connecte-toi à ton serveur FTP
2. Upload `public/*` dans `/www/` (ou `public_html/`)
3. Upload `src/`, `templates/`, `config/`, `migrations/` à la racine `/home/monasso/`

---

### 2. Configurer la base de données

1. Crée une base de données via ton panneau d'hébergement (ex: `monasso_db`)
2. Crée un utilisateur BDD avec mot de passe fort
3. Donne tous les droits sur la base
4. Importe les migrations :

```sql
-- Exécute dans phpMyAdmin ou via CLI
source /home/monasso/migrations/001_create_users_table.sql
source /home/monasso/migrations/002_create_subscriptions_table.sql
source /home/monasso/migrations/003_create_payments_table.sql
source /home/monasso/migrations/004_create_notifications_table.sql
source /home/monasso/migrations/005_create_rgpd_consents_table.sql
```

---

### 3. Configurer les variables d'environnement

**Copie `.env.example` en `.env` et remplis les valeurs :**

```bash
# En local
cp .env.example .env

# En production (via FTP ou SSH)
# Crée le fichier .env à la racine avec tes valeurs
```

**Variables obligatoires :**
```env
APP_NAME=MonAsso
APP_URL=https://monasso.eu
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_DATABASE=monasso_db
DB_USERNAME=ton_utilisateur_bdd
DB_PASSWORD=ton_mot_de_passe_bdd

STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

CPANEL_HOST=https://ton-serveur-cpanel:2083
CPANEL_USERNAME=ton_username
CPANEL_TOKEN=ton_token

APP_KEY=ta_clé_secrète_32_caractères
```

**Ancienne méthode (fichiers de config) :**

Si tu préfères modifier directement les fichiers :

**`/home/monasso/config/database.php`** :
```php
<?php
return [
    'host' => 'localhost',
    'dbname' => 'monasso_db',
    'username' => 'ton_utilisateur_bdd',
    'password' => 'ton_mot_de_passe_bdd',
    'charset' => 'utf8mb4',
];
```

**`/home/monasso/config/stripe.php`** :
```php
<?php
return [
    'public_key' => 'pk_live_...',        // Clé publique Stripe (production)
    'secret_key' => 'sk_live_...',         // Clé secrète Stripe (production)
    'webhook_secret' => 'whsec_...',       // Secret du webhook (Dashboard Stripe)
];
```

**`/home/monasso/config/cpanel.php`** (optionnel) :
```php
<?php
return [
    'host' => 'https://ton-serveur-cpanel:2083',
    'username' => 'ton_username_cpanel',
    'token' => 'ton_token_uapi',
];
```

**`/home/monasso/config/app.php`** :
```php
<?php
return [
    'name' => 'MonAsso',
    'url' => 'https://monasso.eu',
    'support_email' => 'support@monasso.eu',
    'database' => require __DIR__ . '/database.php',
    'stripe' => require __DIR__ . '/stripe.php',
    'cpanel' => require __DIR__ . '/cpanel.php',
];
```

---

### 4. Configurer le webhook Stripe

1. Va sur [Dashboard Stripe](https://dashboard.stripe.com/webhooks)
2. Crée un endpoint : `https://monasso.eu/stripe-webhook.php`
3. Sélectionne les événements :
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
   - `customer.subscription.deleted`
4. Copie le `Signing Secret` dans `config/stripe.php`

---

### 5. Permissions des fichiers

Via FTP ou SSH :
```bash
# Dossiers en lecture/écriture
chmod 755 /home/monasso/src
chmod 755 /home/monasso/templates
chmod 755 /home/monasso/config

# Fichiers de config (sécurisés)
chmod 644 /home/monasso/config/*.php

# Racine web
chmod 755 /home/monasso/www
chmod 644 /home/monasso/www/*.php
```

---

### 6. Tester l'installation

1. Ouvre `https://monasso.eu`
2. Vérifie que la page d'accueil s'affiche
3. Crée un compte test
4. Souscris un abonnement (mode test Stripe avec `pm_card_bancontact` ou cartes test)
5. Vérifie le webhook dans le Dashboard Stripe

---

## 🛡️ Sécurité

- ✅ `src/`, `templates/`, `config/` hors racine web
- ✅ `.htaccess` pour bloquer l'accès direct aux fichiers PHP
- ✅ Tokens CSRF sur les formulaires
- ✅ Rate limiting sur les connexions
- ✅ Headers HTTP sécurisés
- ✅ Validation des entrées utilisateur
- ✅ Mots de passe hashés (PASSWORD_DEFAULT)

---

## 📧 Emails

Pour l'envoi d'emails, la fonction `mail()` de PHP est utilisée.  
Pour une meilleure délivrabilité, configure :

- **SPF** : `v=spf1 include:_spf.google.com ~all`
- **DKIM** : Clé DKIM via ton hébergeur
- **DMARC** : `v=DMARC1; p=none; rua=mailto:dmarc@monasso.eu`

---

## 🔄 Mises à jour

1. Sauvegarde la BDD et les fichiers
2. Upload les nouveaux fichiers via FTP
3. Exécute les nouvelles migrations si besoin
4. Teste en production

---

## 🆘 Support

En cas de problème :
- Vérifie les logs PHP (`error_log`)
- Active le mode debug : `ini_set('display_errors', 1);` dans `public/index.php`
- Consulte les logs Stripe Dashboard

---

**✅ Déploiement terminé !**  
Ta plateforme MonAsso est maintenant en ligne, sécurisée et automatisée.
