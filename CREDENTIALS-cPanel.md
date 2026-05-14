# 🔐 Configuration cPanel - Compte Unique

## 📋 Architecture

MonAsso se connecte à **UN SEUL compte cPanel principal** pour tous les utilisateurs.

```
┌─────────────────────────────────────────┐
│      Compte cPanel : "monasso"          │
│  (héberge toutes les associations)      │
├─────────────────────────────────────────┤
│                                         │
│  /home/monasso/users/                   │
│  ├── association1/                      │
│  ├── association2/                      │
│  ├── sport45/                           │
│  └── culture78/                         │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🔑 Token API cPanel

### 1. Générer le token dans cPanel

1. Connecte-toi à cPanel : `https://ton-serveur:2083`
2. Va dans **Sécurité** → **Gestion des tokens API**
3. Clique sur **Générer un token**
4. Nomme-le : `MonAsso API`
5. Permissions requises :
   - ✅ `Fileman` (création de dossiers)
   - ✅ `Quota` (vérification espace disque)
6. Copie le token généré

### 2. Configurer dans `.env`

```env
CPANEL_HOST=https://ton-serveur-cpanel:2083
CPANEL_PORT=2083
CPANEL_USERNAME=monasso
CPANEL_TOKEN=monasso_ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890
CPANEL_USERS_PATH=/home/monasso/users
```

---

## 📁 Structure des dossiers

### Chemin de base (configurable)
```
/home/monasso/users/
```

### Dossiers créés automatiquement
Chaque association a son dossier :

```
/home/monasso/users/
├── monasso123/           ← Nom de l'association (sanitisé)
│   ├── public_html/      ← Sous-dossiers cPanel standards
│   └── ...
├── sport45/
├── culture78/
└── ...
```

### Nettoyage du nom
Le nom de l'association est transformé :
- Suppression des caractères spéciaux
- Conversion en minuscules
- Ex: "Mon Asso ! 2026" → `monasso2026`

---

## 🔒 Sécurité

### Token API
- **Jamais commité** dans Git (`.env` dans `.gitignore`)
- **Permissions minimales** : uniquement `Fileman`
- **Rotation possible** : régénère le token si compromis

### Isolation des utilisateurs
- Chaque association a son propre dossier
- Pas d'accès croisé entre dossiers
- Permissions UNIX : `755` (lecture/écriture propriétaire)

### Logs
Toutes les actions cPanel sont loguées :
```php
error_log('Provisioning: ' . $user->association . ' → ' . $subdomain);
```

---

## 🚀 Provisioning automatique

### Déclencheur
Le provisioning se fait **après paiement Stripe confirmé** :

```
Paiement réussi
    ↓
Webhook Stripe
    ↓
StripeService::handlePaymentSucceeded()
    ↓
CpanelService::provisionUser($user)
    ↓
Création dossier + Email bienvenue
```

### Code de provisioning

```php
// src/Service/CpanelService.php
public function provisionUser($user) {
    // Génère l'identifiant
    $subdomain = strtolower(preg_replace('/[^a-z0-9]/', '', $user->association));
    
    // Crée le dossier via UAPI
    $result = $this->createUserFolder($subdomain);
    
    // Envoie email de bienvenue
    if ($result) {
        EmailService::sendWelcome($user->email, $user->association);
    }
}
```

---

## 📊 Suivi et monitoring

### Vérifier les dossiers créés

**Via cPanel → File Manager :**
```
/home/monasso/users/
```

**Via SSH :**
```bash
ls -la /home/monasso/users/
```

**Via FTP :**
- Connecte-toi en FTP
- Navigue dans `/home/monasso/users/`

### Logs d'erreurs

```bash
# Logs PHP
tail -f /home/monasso/logs/error.log

# Logs cPanel (si accessibles)
tail -f /usr/local/cpanel/logs/error_log
```

---

## ⚠️ Limitations et bonnes pratiques

### 1. Espace disque
- Vérifie l'espace disponible régulièrement
- Utilise l'API `Quota::get_quota_info` pour monitorer
- Alerte si > 80% utilisé

### 2. Nombre de dossiers
- cPanel n'impose pas de limite stricte
- Mais surveille les performances si > 1000 dossiers

### 3. Noms de dossiers
- Évite les doublons (ajoute un ID si besoin)
- Limite la longueur (max 50 caractères)

### 4. Permissions
- Toujours `755` pour les dossiers
- Propriétaire : `monasso:monasso`

---

## 🔄 Rotation du token (optionnel)

Si tu veux changer le token périodiquement :

1. Génère un nouveau token dans cPanel
2. Met à jour `.env` :
   ```env
   CPANEL_TOKEN=nouveau_token_xyz
   ```
3. Redémarre PHP-FPM (si nécessaire) :
   ```bash
   sudo systemctl restart php-fpm
   ```

---

## 🆘 Dépannage

### Erreur : "Token invalide"
- Vérifie que le token est bien copié (pas d'espace)
- Régénère un token dans cPanel
- Vérifie les permissions du token

### Erreur : "Permission denied"
- Vérifie que le token a la permission `Fileman`
- Vérifie les permissions UNIX du dossier `users/`

### Erreur : "Directory not found"
- Crée manuellement `/home/monasso/users/`
- Vérifie le chemin dans `CPANEL_USERS_PATH`

### Erreur : "SSL certificate error"
- Utilise un certificat valide sur cPanel
- Ou désactive la vérification (développement uniquement)

---

## ✅ Checklist de configuration

- [ ] Token API généré dans cPanel
- [ ] Permissions `Fileman` accordées
- [ ] `.env` configuré avec le token
- [ ] Dossier `/home/monasso/users/` créé
- [ ] Permissions `755` définies
- [ ] Test de provisioning effectué
- [ ] Logs surveillés

---

**📌 Résumé :** Un seul compte cPanel, tous les dossiers utilisateurs dedans, provisioning automatique après paiement.
