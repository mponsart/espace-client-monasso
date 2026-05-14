# 📁 Dossiers Utilisateurs - Paheko

## 📍 Chemin par défaut

Sur Paheko, les dossiers utilisateurs sont stockés dans :

```
/home/monasso/users/
├── association1/
├── association2/
├── user123/
└── ...
```

---

## ⚙️ Configuration

### Variable `.env`

```env
# Chemin absolu vers les dossiers utilisateurs
CPANEL_USERS_PATH=/home/monasso/users
```

### Fichier `config/cpanel.php`

```php
'users_path' => EnvLoader::get('CPANEL_USERS_PATH', '/home/monasso/users'),
```

---

## 🔧 Personnalisation du chemin

### Cas 1 : Hébergement mutualisé standard
```env
CPANEL_USERS_PATH=/home/monasso/users
```

### Cas 2 : Dossier personnalisé (ex: `www/users`)
```env
CPANEL_USERS_PATH=/home/monasso/www/users
```

### Cas 3 : Sous-domaine dédié (ex: `users.monasso.eu`)
```env
CPANEL_USERS_PATH=/home/monasso/public_html/users
```

### Cas 4 : Racine du compte cPanel
```env
CPANEL_USERS_PATH=/home/monasso
```

---

## 📋 Vérification du chemin

### Via SSH (si accès disponible)
```bash
# Se connecter en SSH
ssh monasso@ton-serveur-paheko

# Lister le contenu
ls -la /home/monasso/

# Vérifier les permissions
ls -ld /home/monasso/users
```

### Via PHP (script de test)
```php
<?php
// test-path.php
echo "Utilisateur : " . posix_getpwuid(posix_getuid())['name'] . "\n";
echo "Dossier home : " . posix_getpwuid(posix_getuid())['dir'] . "\n";
echo "PWD : " . getcwd() . "\n";
?>
```

### Via FileZilla / FTP
1. Connecte-toi en FTP
2. Navigue dans `/home/monasso/`
3. Vérifie si le dossier `users/` existe
4. Sinon, crée-le manuellement

---

## 🛡️ Permissions requises

### Dossier principal
```bash
# Dossier users
chmod 755 /home/monasso/users
chown monasso:monasso /home/monasso/users
```

### Sous-dossiers (créés automatiquement)
```bash
# Chaque dossier association
chmod 755 /home/monasso/users/association123
chown monasso:monasso /home/monasso/users/association123
```

---

## 📧 Structure recommandée

```
/home/monasso/
├── users/                    ← Dossiers des associations
│   ├── monasso123/
│   ├── sport45/
│   └── culture78/
├── www/                      ← Racine web (public)
│   ├── index.php
│   └── ...
├── src/                      ← Code source (hors web)
├── logs/                     ← Logs applicatifs
└── .env                      ← Configuration
```

---

## 🚀 Création manuelle (si nécessaire)

### Via cPanel → File Manager
1. Ouvre cPanel → **File Manager**
2. Navigue dans `/home/monasso/`
3. Crée un nouveau dossier : `users`
4. Permissions : `755`

### Via SSH
```bash
cd /home/monasso
mkdir -p users
chmod 755 users
```

### Via FTP (FileZilla)
1. Crée un dossier `users` à la racine
2. Clic droit → **Permissions**
3. Coche : `Read`, `Write`, `Execute` (propriétaire)
4. Coche : `Read`, `Execute` (groupe, public)

---

## ✅ Checklist de configuration

- [ ] Vérifier le chemin réel via SSH ou FTP
- [ ] Créer le dossier `users/` s'il n'existe pas
- [ ] Définir les permissions `755`
- [ ] Configurer `CPANEL_USERS_PATH` dans `.env`
- [ ] Tester le provisioning avec un compte test

---

## 🔍 Dépannage

### Erreur : "Permission denied"
```bash
# Vérifie le propriétaire
ls -la /home/monasso/

# Corrige les permissions
chown -R monasso:monasso /home/monasso/users
chmod -R 755 /home/monasso/users
```

### Erreur : "Directory not found"
```bash
# Crée le dossier manuellement
mkdir -p /home/monasso/users
chmod 755 /home/monasso/users
```

### Erreur cPanel UAPI
- Vérifie que le token API a les permissions **Fileman**
- Teste manuellement via cPanel → File Manager
- Consulte les logs : `/home/monasso/logs/error.log`

---

**📌 Important :** Le chemin `/home/monasso/users/` est le défaut pour Paheko.  
Adapte-le selon ta configuration réelle (consulte ton espace client Paheko si besoin).
