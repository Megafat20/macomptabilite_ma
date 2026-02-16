# 📤 Guide Upload via cPanel - Gestion Facturation

## 🎯 Méthode Recommandée : Gestionnaire de Fichiers cPanel

Cette méthode est **simple**, **rapide** et ne nécessite aucun logiciel supplémentaire !

---

## 📋 Étape 1 : Préparer les Fichiers Localement

### A. Créer une archive ZIP de votre application

1. **Ouvrez votre dossier** : `C:\laragon\www\gestion_facturation`

2. **Créez 2 archives ZIP séparées** :

#### 📦 Archive 1 : `laravel_app.zip`

Compressez TOUS les fichiers **SAUF** le dossier `public/` :

-   ✅ app/
-   ✅ bootstrap/
-   ✅ config/
-   ✅ database/
-   ✅ resources/
-   ✅ routes/
-   ✅ storage/
-   ✅ vendor/
-   ✅ .env (votre fichier de production)
-   ✅ artisan
-   ✅ composer.json
-   ✅ composer.lock
-   ❌ **PAS le dossier public/**

**Comment faire :**

1. Sélectionnez tous les dossiers/fichiers SAUF `public/`
2. Clic droit > **Envoyer vers** > **Dossier compressé (zip)**
3. Renommez en `laravel_app.zip`

#### 📦 Archive 2 : `public_files.zip`

Compressez **UNIQUEMENT** le contenu du dossier `public/` :

1. Entrez dans le dossier `public/`
2. Sélectionnez **tout le contenu** à l'intérieur
3. Clic droit > **Envoyer vers** > **Dossier compressé (zip)**
4. Renommez en `public_files.zip`

#### 📄 Fichier 3 : `install.php`

Gardez ce fichier séparé (ne le zippez pas)

---

## 🌐 Étape 2 : Connexion à cPanel

1. **Ouvrez votre navigateur**
2. **Accédez à votre cPanel** :

    ```
    https://votredomaine.com:2083
    ou
    https://votredomaine.com/cpanel
    ou
    Le lien fourni par Najahost
    ```

3. **Connectez-vous** avec :
    - **Nom d'utilisateur** : Votre username cPanel
    - **Mot de passe** : Votre mot de passe cPanel

---

## 📁 Étape 3 : Accéder au Gestionnaire de Fichiers

1. Dans le **tableau de bord cPanel**, cherchez la section **FICHIERS**
2. Cliquez sur **Gestionnaire de fichiers** (File Manager)
3. Une nouvelle fenêtre/onglet s'ouvre

---

## 🗂️ Étape 4 : Créer la Structure des Dossiers

### A. Créer le dossier `laravel_app`

1. Dans le gestionnaire de fichiers, vous êtes dans `/home/votre_username/`
2. Cliquez sur **+ Dossier** (ou New Folder) dans la barre d'outils
3. Nommez-le : `laravel_app`
4. Cliquez sur **Create New Folder**

### B. Vérifier le dossier `public_html`

Le dossier `public_html` existe déjà (c'est le dossier racine de votre site web).

Votre structure doit ressembler à :

```
/home/votre_username/
├── laravel_app/          ← Vous venez de le créer
├── public_html/          ← Existe déjà
├── public_ftp/
├── mail/
└── ...
```

---

## ⬆️ Étape 5 : Uploader laravel_app.zip

1. **Double-cliquez** sur le dossier `laravel_app` pour y entrer
2. Cliquez sur **Téléverser** (Upload) dans la barre d'outils
3. Une fenêtre de téléversement s'ouvre
4. Cliquez sur **Sélectionner un fichier** (Select File)
5. Naviguez vers `laravel_app.zip` sur votre ordinateur
6. Sélectionnez-le et cliquez **Ouvrir**
7. **Attendez** que la barre de progression atteigne 100%
8. Fermez la fenêtre de téléversement

---

## 📦 Étape 6 : Extraire laravel_app.zip

1. Dans le dossier `laravel_app`, vous devriez voir `laravel_app.zip`
2. **Clic droit** sur `laravel_app.zip`
3. Sélectionnez **Extract** (Extraire)
4. Une boîte de dialogue apparaît
5. Vérifiez que le chemin est : `/home/votre_username/laravel_app`
6. Cliquez sur **Extract File(s)**
7. Attendez l'extraction (peut prendre 1-2 minutes)
8. Cliquez sur **Close** quand c'est terminé

### Vérification

Vous devriez maintenant voir dans `laravel_app/` :

-   app/
-   bootstrap/
-   config/
-   database/
-   resources/
-   routes/
-   storage/
-   vendor/
-   .env
-   artisan
-   composer.json
-   etc.

### Nettoyage

1. **Sélectionnez** `laravel_app.zip`
2. Cliquez sur **Delete** (Supprimer)
3. Confirmez la suppression

---

## ⬆️ Étape 7 : Uploader public_files.zip

1. **Retournez** au dossier parent : cliquez sur **Up One Level** (ou cliquez sur `/home/votre_username/`)
2. **Double-cliquez** sur le dossier `public_html` pour y entrer
3. Cliquez sur **Téléverser** (Upload)
4. Sélectionnez **public_files.zip**
5. Attendez la fin du téléversement
6. Fermez la fenêtre de téléversement

---

## 📦 Étape 8 : Extraire public_files.zip

1. Dans `public_html`, **clic droit** sur `public_files.zip`
2. Sélectionnez **Extract**
3. Vérifiez le chemin : `/home/votre_username/public_html`
4. Cliquez sur **Extract File(s)**
5. Attendez l'extraction
6. Cliquez sur **Close**

### Vérification

Dans `public_html/`, vous devriez voir :

-   index.php
-   .htaccess
-   css/
-   js/
-   favicon.ico
-   etc.

### Nettoyage

Supprimez `public_files.zip` comme précédemment.

---

## 📄 Étape 9 : Uploader install.php

1. Toujours dans `public_html`
2. Cliquez sur **Téléverser** (Upload)
3. Sélectionnez le fichier `install.php` depuis votre ordinateur
4. Attendez la fin du téléversement
5. Fermez la fenêtre

---

## ✏️ Étape 10 : Modifier index.php

### C'est l'étape la PLUS IMPORTANTE ! 🔴

1. Dans `public_html`, localisez le fichier `index.php`
2. **Clic droit** sur `index.php`
3. Sélectionnez **Edit** (ou **Code Edit**)
4. Si un pop-up apparaît, cliquez sur **Edit** à nouveau

### Modifications à apporter :

**Cherchez ces lignes (vers le début du fichier) :**

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**Remplacez par :**

```php
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

5. Cliquez sur **Save Changes** (en haut à droite)
6. Fermez l'éditeur

---

## 🔐 Étape 11 : Définir les Permissions

### A. Permissions pour storage/

1. Retournez à `/home/votre_username/`
2. Entrez dans `laravel_app/`
3. **Clic droit** sur le dossier `storage`
4. Sélectionnez **Change Permissions** (ou **Permissions**)
5. Cochez les cases pour obtenir **755** :
    - User: Read ✅ Write ✅ Execute ✅
    - Group: Read ✅ Execute ✅
    - World: Read ✅ Execute ✅
6. ✅ **Cochez** "Recurse into subdirectories"
7. Cliquez sur **Change Permissions**

### B. Permissions pour bootstrap/cache/

1. **Clic droit** sur le dossier `bootstrap`
2. Entrez dedans
3. **Clic droit** sur `cache`
4. **Change Permissions** → **755**
5. ✅ Cochez "Recurse into subdirectories"
6. **Change Permissions**

---

## 🎯 Étape 12 : Vérification Finale

### Checklist de vérification :

Dans le gestionnaire de fichiers, vérifiez que :

**Dans `/home/votre_username/laravel_app/` :**

-   ✅ Tous les dossiers Laravel sont présents
-   ✅ Le fichier `.env` existe
-   ✅ Permissions 755 sur `storage/` et `bootstrap/cache/`

**Dans `/home/votre_username/public_html/` :**

-   ✅ `index.php` est présent et modifié
-   ✅ `.htaccess` est présent
-   ✅ `install.php` est présent
-   ✅ Dossiers `css/` et `js/` sont présents

---

## 🚀 Étape 13 : Lancer l'Installation

1. **Ouvrez un nouvel onglet** dans votre navigateur
2. Visitez : `https://votredomaine.com/install.php`
3. Suivez l'assistant d'installation (5 étapes)
4. **⚠️ IMPORTANT** : Après l'installation, supprimez `install.php` !

### Pour supprimer install.php :

1. Dans cPanel > Gestionnaire de fichiers
2. Allez dans `public_html`
3. Clic droit sur `install.php` > **Delete**
4. Confirmez

---

## ✅ C'est Terminé !

Votre application est maintenant déployée ! 🎉

Visitez : `https://votredomaine.com` pour l'utiliser !

---

## 🆘 Problèmes Courants

### "File Manager ne s'ouvre pas"

→ Essayez un autre navigateur (Chrome, Firefox)

### "Upload trop lent"

→ Votre connexion internet. C'est normal pour ~100-200 MB

### "Erreur lors de l'extraction"

→ Vérifiez que vous avez assez d'espace disque sur votre hébergement

### "Permission denied"

→ Contactez le support Najahost pour vérifier les permissions de votre compte

---

## 💡 Astuces

-   **Utilisez Chrome** pour le gestionnaire de fichiers cPanel
-   **Ne fermez pas** la fenêtre pendant l'upload
-   **Vérifiez votre espace disque** avant d'uploader
-   **Gardez une copie** de vos fichiers ZIP en local

---

**Bon déploiement ! 🚀**
