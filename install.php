<?php
/**
 * Script d'Installation Laravel pour Najahost
 * 
 * IMPORTANT: Supprimez ce fichier après l'installation pour des raisons de sécurité !
 * 
 * Instructions:
 * 1. Uploadez ce fichier dans public_html/
 * 2. Visitez https://votredomaine.com/install.php
 * 3. Suivez les instructions
 * 4. SUPPRIMEZ ce fichier après l'installation
 */

// Définir le chemin vers Laravel
define('LARAVEL_PATH', __DIR__ . '/../laravel_app');

// Vérifier si Laravel existe
if (!file_exists(LARAVEL_PATH . '/artisan')) {
    die('❌ Erreur : Laravel n\'est pas installé correctement. Vérifiez que le dossier laravel_app existe.');
}

// Fonction pour exécuter des commandes Artisan
function runArtisan($command) {
    $output = [];
    $result = 0;
    
    exec('cd ' . LARAVEL_PATH . ' && php artisan ' . $command . ' 2>&1', $output, $result);
    
    return [
        'success' => $result === 0,
        'output' => implode("\n", $output)
    ];
}

// Vérifier les permissions
function checkPermissions() {
    $dirs = [
        LARAVEL_PATH . '/storage',
        LARAVEL_PATH . '/bootstrap/cache'
    ];
    
    $issues = [];
    foreach ($dirs as $dir) {
        if (!is_writable($dir)) {
            $issues[] = $dir;
        }
    }
    
    return $issues;
}

// Traitement du formulaire
$step = isset($_GET['step']) ? $_GET['step'] : 1;
$messages = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'check_permissions':
                $issues = checkPermissions();
                if (empty($issues)) {
                    $messages[] = '✅ Toutes les permissions sont correctes';
                    $step = 2;
                } else {
                    $errors[] = '❌ Problèmes de permissions sur : ' . implode(', ', $issues);
                }
                break;
                
            case 'create_env':
                $envContent = "APP_NAME=\"Gestion Facturation\"\n";
                $envContent .= "APP_ENV=production\n";
                $envContent .= "APP_KEY=" . $_POST['app_key'] . "\n";
                $envContent .= "APP_DEBUG=false\n";
                $envContent .= "APP_URL=" . $_POST['app_url'] . "\n\n";
                $envContent .= "DB_CONNECTION=mysql\n";
                $envContent .= "DB_HOST=localhost\n";
                $envContent .= "DB_PORT=3306\n";
                $envContent .= "DB_DATABASE=" . $_POST['db_name'] . "\n";
                $envContent .= "DB_USERNAME=" . $_POST['db_user'] . "\n";
                $envContent .= "DB_PASSWORD=" . $_POST['db_pass'] . "\n";
                
                if (file_put_contents(LARAVEL_PATH . '/.env', $envContent)) {
                    $messages[] = '✅ Fichier .env créé avec succès';
                    $step = 3;
                } else {
                    $errors[] = '❌ Impossible de créer le fichier .env';
                }
                break;
                
            case 'run_migrations':
                $result = runArtisan('migrate --force');
                if ($result['success']) {
                    $messages[] = '✅ Migrations exécutées avec succès';
                    $messages[] = '<pre>' . htmlspecialchars($result['output']) . '</pre>';
                } else {
                    $errors[] = '❌ Erreur lors des migrations';
                    $errors[] = '<pre>' . htmlspecialchars($result['output']) . '</pre>';
                }
                $step = 4;
                break;
                
            case 'optimize':
                $commands = [
                    'storage:link' => 'Création du lien symbolique storage',
                    'config:cache' => 'Cache de configuration',
                    'route:cache' => 'Cache des routes',
                    'view:cache' => 'Cache des vues'
                ];
                
                foreach ($commands as $cmd => $desc) {
                    $result = runArtisan($cmd);
                    if ($result['success']) {
                        $messages[] = "✅ $desc";
                    } else {
                        $errors[] = "❌ Erreur: $desc";
                    }
                }
                $step = 5;
                break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Gestion Facturation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 700px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background: #f0f0f0;
            margin: 0 5px;
            border-radius: 5px;
            font-size: 12px;
            color: #999;
        }
        .step.active {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        .step.completed {
            background: #48bb78;
            color: white;
        }
        .message {
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid;
        }
        .success {
            background: #f0fff4;
            border-color: #48bb78;
            color: #2f855a;
        }
        .error {
            background: #fff5f5;
            border-color: #f56565;
            color: #c53030;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        input[type="text"], input[type="password"], input[type="url"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .warning {
            background: #fefcbf;
            border: 2px solid #f6e05e;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            color: #744210;
        }
        pre {
            background: #2d3748;
            color: #f7fafc;
            padding: 15px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 12px;
        }
        .final-message {
            text-align: center;
            padding: 30px;
        }
        .final-message h2 {
            color: #48bb78;
            margin-bottom: 20px;
        }
        .btn-delete {
            background: #f56565;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Installation Gestion Facturation</h1>
        <p class="subtitle">Assistant d'installation pour Najahost</p>
        
        <div class="step-indicator">
            <div class="step <?php echo $step >= 1 ? 'active' : ''; ?> <?php echo $step > 1 ? 'completed' : ''; ?>">1. Permissions</div>
            <div class="step <?php echo $step >= 2 ? 'active' : ''; ?> <?php echo $step > 2 ? 'completed' : ''; ?>">2. Configuration</div>
            <div class="step <?php echo $step >= 3 ? 'active' : ''; ?> <?php echo $step > 3 ? 'completed' : ''; ?>">3. Migration</div>
            <div class="step <?php echo $step >= 4 ? 'active' : ''; ?> <?php echo $step > 4 ? 'completed' : ''; ?>">4. Optimisation</div>
            <div class="step <?php echo $step >= 5 ? 'active' : ''; ?>">5. Terminé</div>
        </div>
        
        <?php foreach ($messages as $msg): ?>
            <div class="message success"><?php echo $msg; ?></div>
        <?php endforeach; ?>
        
        <?php foreach ($errors as $err): ?>
            <div class="message error"><?php echo $err; ?></div>
        <?php endforeach; ?>
        
        <?php if ($step == 1): ?>
            <div class="warning">
                ⚠️ <strong>Important :</strong> Assurez-vous que les dossiers <code>storage/</code> et <code>bootstrap/cache/</code> ont les permissions 755.
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="check_permissions">
                <button type="submit">Vérifier les Permissions</button>
            </form>
            
        <?php elseif ($step == 2): ?>
            <h3>Configuration de la Base de Données</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create_env">
                
                <div class="form-group">
                    <label>URL de l'Application</label>
                    <input type="url" name="app_url" value="https://<?php echo $_SERVER['HTTP_HOST']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Clé d'Application (APP_KEY)</label>
                    <input type="text" name="app_key" value="base64:<?php echo base64_encode(random_bytes(32)); ?>" required readonly>
                    <small style="color: #666;">Cette clé a été générée automatiquement</small>
                </div>
                
                <div class="form-group">
                    <label>Nom de la Base de Données</label>
                    <input type="text" name="db_name" required placeholder="nom_base_donnees">
                </div>
                
                <div class="form-group">
                    <label>Utilisateur MySQL</label>
                    <input type="text" name="db_user" required placeholder="utilisateur">
                </div>
                
                <div class="form-group">
                    <label>Mot de Passe MySQL</label>
                    <input type="password" name="db_pass" required placeholder="motdepasse">
                </div>
                
                <button type="submit">Créer la Configuration</button>
            </form>
            
        <?php elseif ($step == 3): ?>
            <h3>Migration de la Base de Données</h3>
            <div class="warning">
                ⚠️ Cette étape va créer toutes les tables nécessaires dans votre base de données.
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="run_migrations">
                <button type="submit">Exécuter les Migrations</button>
            </form>
            
        <?php elseif ($step == 4): ?>
            <h3>Optimisation de l'Application</h3>
            <p>Cette étape va créer le lien symbolique storage et mettre en cache la configuration.</p>
            <form method="POST">
                <input type="hidden" name="action" value="optimize">
                <button type="submit">Optimiser l'Application</button>
            </form>
            
        <?php elseif ($step == 5): ?>
            <div class="final-message">
                <h2>🎉 Installation Terminée !</h2>
                <p style="color: #666; margin: 20px 0;">
                    Votre application Laravel est maintenant installée et configurée.
                </p>
                <div class="message success">
                    ✅ Vous pouvez maintenant accéder à votre application :<br>
                    <strong><a href="/" style="color: #667eea;">Accéder à Gestion Facturation</a></strong>
                </div>
                
                <div class="warning" style="margin-top: 30px;">
                    <strong>🔒 SÉCURITÉ IMPORTANTE :</strong><br>
                    Pour des raisons de sécurité, vous DEVEZ supprimer ce fichier install.php immédiatement !
                </div>
                
                <p style="margin-top: 20px; color: #e53e3e; font-weight: bold;">
                    Supprimez install.php via votre gestionnaire de fichiers cPanel ou FTP
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
