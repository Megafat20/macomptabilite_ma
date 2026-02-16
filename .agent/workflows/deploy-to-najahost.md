---
description: Déployer l'application sur Najahost
---

# Guide de Déploiement sur Najahost

## 1. Préparation de l'Application Locale

### A. Optimiser Laravel pour la Production

```bash
# Vider tous les caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### B. Configurer le fichier .env pour la production

Créez un nouveau fichier `.env.production` avec :

```env
APP_NAME="Gestion Facturation"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_ICI
APP_DEBUG=false
APP_URL=https://votredomaine.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nom_base_donnees
DB_USERNAME=utilisateur_bdd
DB_PASSWORD=mot_de_passe_bdd

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### C. Mettre à jour composer pour la production

```bash
composer install --optimize-autoloader --no-dev
```

## 2. Upload des Fichiers sur Najahost

### A. Connexion FTP/SFTP

-   **Hôte** : ftp.votredomaine.com (ou IP fournie)
-   **Utilisateur** : Votre username cPanel
-   **Mot de passe** : Votre mot de passe cPanel
-   **Port** : 21 (FTP) ou 22 (SFTP)

Utilisez FileZilla, WinSCP, ou le gestionnaire de fichiers cPanel.

### B. Structure des fichiers sur le serveur

```
/home/username/
├── public_html/           # Dossier racine web (pointe vers Laravel/public)
│   ├── index.php         # Copier depuis Laravel/public
│   ├── .htaccess         # Copier depuis Laravel/public
│   ├── css/
│   ├── js/
│   └── ...
├── laravel_app/          # Créer ce dossier pour le reste de Laravel
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── composer.json
```

### C. Uploadez les fichiers

1. Créez un dossier `laravel_app` à la racine de votre hébergement
2. Uploadez TOUS les fichiers SAUF `public/` dans `laravel_app/`
3. Uploadez le contenu de `public/` dans `public_html/`

## 3. Configuration du Serveur

### A. Modifier `public_html/index.php`

Ouvrez `public_html/index.php` et modifiez les chemins :

**AVANT :**

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**APRÈS :**

```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

### B. Créer le fichier .htaccess dans public_html

Si absent, créez `.htaccess` dans `public_html/` :

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

OU si Laravel est déjà dans public_html :

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 4. Configuration de la Base de Données

### A. Créer la base de données via cPanel

1. Accédez à **MySQL Databases** dans cPanel
2. Créez une nouvelle base de données
3. Créez un utilisateur MySQL
4. Associez l'utilisateur à la base de données avec TOUS les privilèges

### B. Importer votre base de données

1. Accédez à **phpMyAdmin** dans cPanel
2. Sélectionnez votre base de données
3. Cliquez sur **Importer**
4. Uploadez votre fichier SQL exporté depuis votre environnement local

### C. Configurer .env

Modifiez `laravel_app/.env` avec les informations de connexion :

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nomdevotrebase
DB_USERNAME=utilisateurmysql
DB_PASSWORD=motdepassemysql
```

## 5. Permissions des Dossiers

Définissez les bonnes permissions via SSH ou FTP :

```bash
chmod -R 755 laravel_app/storage
chmod -R 755 laravel_app/bootstrap/cache
```

Si vous avez accès SSH :

```bash
cd laravel_app
chown -R username:username storage bootstrap/cache
```

## 6. Finalisation et Migration

### A. Via SSH (si disponible)

```bash
cd laravel_app
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### B. Sans SSH

-   Accédez à `votredomaine.com/install.php` (créez un script d'installation temporaire)
-   Ou utilisez un outil comme **Laravel Forge** ou **Envoyer**

## 7. Sécurité

### A. Forcer HTTPS

Ajoutez dans `public_html/.htaccess` :

```apache
# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### B. Masquer les fichiers sensibles

Dans `.htaccess` :

```apache
# Deny access to .env
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

### C. Mettre à jour .env

```env
APP_DEBUG=false
APP_ENV=production
```

## 8. Vérification

1. ✅ Visitez votre domaine : `https://votredomaine.com`
2. ✅ Testez la connexion (login/register)
3. ✅ Vérifiez les factures, entreprises, etc.
4. ✅ Testez l'upload de fichiers
5. ✅ Vérifiez les logs : `laravel_app/storage/logs/laravel.log`

## 9. Maintenance

### Mettre à jour l'application

1. Modifiez en local
2. Uploadez uniquement les fichiers modifiés via FTP
3. Videz le cache :
    ```bash
    php artisan config:clear
    php artisan cache:clear
    ```

### Monitoring

-   Activez les logs dans `config/logging.php`
-   Surveillez `storage/logs/laravel.log`
-   Configurez des sauvegardes automatiques de la BDD dans cPanel

## 🆘 Dépannage

### Erreur 500

-   Vérifiez `storage/logs/laravel.log`
-   Assurez-vous que `APP_DEBUG=false`
-   Vérifiez les permissions (755 pour storage/)

### Page blanche

-   Vérifiez les chemins dans `index.php`
-   Vérifiez `.env` (APP_KEY doit être définie)

### Assets non chargés (CSS/JS)

-   Vérifiez `APP_URL` dans `.env`
-   Régénérez les assets : `npm run build` puis uploadez