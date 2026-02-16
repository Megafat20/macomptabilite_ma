# 🎯 DÉBUT ICI - Déploiement Laravel sur Najahost via cPanel

**Vous venez de créer un nom de domaine sur Najahost ?**
**Vous voulez déployer votre application Gestion Facturation ?**

Ce fichier vous guide vers les bonnes ressources !

---

## 🚀 COMMENCEZ PAR ICI

### Pour les pressés (15 minutes)

👉 **Ouvrez : `CPANEL_CHEATSHEET.md`**

-   Version ultra-condensée
-   Juste les commandes essentielles
-   Parfait si vous savez déjà ce que vous faites

### Pour un guide visuel détaillé (30 minutes)

👉 **Ouvrez : `CPANEL_UPLOAD_GUIDE.md`** ⭐ RECOMMANDÉ

-   Guide pas à pas avec explications
-   Parfait pour les débutants
-   Inclut des vérifications à chaque étape

### Pour un aperçu global

👉 **Ouvrez : `DEPLOYMENT_GUIDE_VISUAL.txt`**

-   Vue d'ensemble avec ASCII art
-   Structure visuelle claire
-   Checklist finale incluse

### Pour une checklist complète

👉 **Ouvrez : `DEPLOYMENT_CHECKLIST.md`**

-   Cochez au fur et à mesure
-   Ne manquez aucune étape
-   Parfait pour suivre votre progression

---

## 📚 RESSOURCES TECHNIQUES

### Guide détaillé complet

📖 `.agent/workflows/deploy-to-najahost.md`

-   Guide technique approfondi
-   Toutes les configurations possibles
-   Dépannage avancé

### Guides de démarrage

-   `QUICK_START.md` - Version résumée
-   `DEPLOYMENT_README.md` - Vue d'ensemble

---

## 🔧 OUTILS INCLUS

### Script d'export BDD

📄 `export-database.bat`

-   Double-cliquez pour exporter votre base de données
-   Crée un fichier .sql automatiquement
-   À uploader sur Najahost via phpMyAdmin

### Script d'installation serveur

📄 `install.php`

-   À uploader dans public_html/
-   Automatise l'installation sur le serveur
-   Interface web intuitive
-   ⚠️ À SUPPRIMER après installation !

### Fichiers de configuration

-   `.env.production.example` - Template .env pour production
-   `.htaccess.production` - Configuration Apache optimisée

---

## 🎯 RÉSUMÉ EXPRESS

```
1. Export BDD          → export-database.bat
2. Créer 2 ZIP         → laravel_app.zip + public_files.zip
3. Connexion cPanel    → https://votredomaine.com:2083
4. Créer BDD MySQL     → cPanel > MySQL DatabasesS
5. Upload fichiers     → Gestionnaire de fichiers
6. Modifier index.php  → Chemins vers laravel_app/
7. Permissions         → 755 sur storage/ et cache/
8. Installation        → votredomaine.com/install.php
9. SUPPRIMER           → install.php
10. TESTÉ !            → votredomaine.com
```

---

## 📞 BESOIN D'AIDE ?

### Erreurs courantes

-   **Erreur 500** → Vérifiez `laravel_app/storage/logs/laravel.log`
-   **Page blanche** → Vérifiez les chemins dans `public_html/index.php`
-   **BDD erreur** → Vérifiez `.env` dans `laravel_app/`
-   **CSS manquant** → Vérifiez `APP_URL` dans `.env`

### Support

-   📖 Consultez les guides détaillés
-   🔍 Vérifiez toujours les logs Laravel
-   💬 Support Najahost pour problèmes serveur

---

## ✅ VOTRE CHECKLIST RAPIDE

Avant de commencer, assurez-vous d'avoir :

-   [ ] Accès à votre cPanel Najahost
-   [ ] Votre nom de domaine configuré
-   [ ] L'application fonctionne en local
-   [ ] 60 minutes devant vous
-   [ ] Une bonne connexion Internet

---

## 🎉 APRÈS LE DÉPLOIEMENT

Une fois terminé :

-   ✅ Activez le SSL (Let's Encrypt via cPanel)
-   ✅ Configurez les sauvegardes automatiques
-   ✅ Testez toutes les fonctionnalités
-   ✅ Partagez votre application !

---

**Temps total estimé : 45-60 minutes**

**Commencez maintenant ! 🚀**

👉 Ouvrez `CPANEL_UPLOAD_GUIDE.md` pour débuter

---

_Créé par l'assistant de déploiement Gestion Facturation_
_Bonne chance ! 💪_
