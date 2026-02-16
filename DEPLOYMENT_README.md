# 🚀 Déploiement de Gestion Facturation sur Najahost

Votre application **Gestion Facturation** est prête à être déployée sur votre nouveau domaine Najahost !

## 📁 Fichiers Créés pour le Déploiement

Voici tous les fichiers qui ont été créés pour faciliter votre déploiement :

| Fichier                                  | Description                                       |
| ---------------------------------------- | ------------------------------------------------- |
| `QUICK_START.md`                         | 📖 **Guide rapide** - Commencez ici !             |
| `CPANEL_UPLOAD_GUIDE.md`                 | 📤 **Guide Upload cPanel** - Détaillé avec images |
| `CPANEL_CHEATSHEET.md`                   | ⚡ **Aide-mémoire cPanel** - Version ultra-rapide |
| `DEPLOYMENT_CHECKLIST.md`                | ✅ Checklist complète de déploiement              |
| `.agent/workflows/deploy-to-najahost.md` | 📚 Guide détaillé étape par étape                 |
| `install.php`                            | 🔧 Script d'installation automatique (à uploader) |
| `export-database.bat`                    | 💾 Export automatique de votre BDD                |
| `.env.production.example`                | ⚙️ Exemple de configuration production            |
| `.htaccess.production`                   | 🔒 Fichier .htaccess sécurisé                     |

---

## 🎯 Par où commencer ?

### Option 1 : Guide Rapide (Recommandé)

**Pour un déploiement rapide** → Ouvrez `QUICK_START.md`

### Option 2 : Guide Complet

**Pour des instructions détaillées** → Ouvrez `.agent/workflows/deploy-to-najahost.md`

### Option 3 : Checklist

**Pour suivre votre progression** → Ouvrez `DEPLOYMENT_CHECKLIST.md`

---

## 📋 Résumé des Étapes

1. **Exporter la BDD** : Double-cliquez sur `export-database.bat`
2. **Configurer .env** : Copiez `.env.production.example` → `.env`
3. **Créer BDD sur Najahost** : Via cPanel > MySQL Databases
4. **Uploader les fichiers** :
    - `laravel_app/` : Tous les fichiers sauf `public/`
    - `public_html/` : Contenu de `public/` + `install.php`
5. **Modifier index.php** : Ajustez les chemins
6. **Importer BDD** : Via phpMyAdmin
7. **Installation** : Visitez `votredomaine.com/install.php`
8. **Supprimer install.php** : ⚠️ Important pour la sécurité !

---

## 🔑 Informations Importantes

### Clé d'Application Générée

Votre nouvelle clé d'application pour la production :

```
base64:KHLxZU53J6lRBBDWWyCOLZGWcqulW6v4x9FIJwUBjAg=
```

> Utilisez cette clé dans votre fichier `.env` sur Najahost

### Structure des Dossiers sur Najahost

```
/home/votre_username/
  ├── laravel_app/          ← Application Laravel
  └── public_html/          ← Dossier public uniquement
```

---

## 🆘 Besoin d'Aide ?

### Problèmes Courants

**Erreur 500**

```bash
# Vérifiez les logs
laravel_app/storage/logs/laravel.log
```

**Page Blanche**

```bash
# Vérifiez les chemins dans
public_html/index.php
```

**BDD Non Connectée**

```bash
# Vérifiez le fichier
laravel_app/.env
```

### Support

-   📖 Consultez les guides dans le dossier du projet
-   💬 Contactez le support Najahost si problème serveur
-   🔍 Vérifiez toujours les logs Laravel en premier

---

## ✨ Fonctionnalités de l'Application

-   ✅ Gestion des factures (Actif/Passif)
-   ✅ Gestion des entreprises
-   ✅ Dashboard avec statistiques
-   ✅ Export Excel/CSV
-   ✅ Upload de pièces jointes
-   ✅ Filtres avancés
-   ✅ Graphiques interactifs
-   ✅ Interface responsive

---

## 📞 Après le Déploiement

Une fois déployé :

1. Testez toutes les fonctionnalités
2. Configurez les sauvegardes automatiques
3. Activez le certificat SSL (Let's Encrypt)
4. Partagez le lien avec vos utilisateurs ! 🎉

---

**Bon déploiement ! 🚀**

Si vous avez des questions, consultez les guides détaillés ou le workflow `/deploy-to-najahost`.
