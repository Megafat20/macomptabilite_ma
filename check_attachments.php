<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$attachments = \App\Models\Attachment::all();
foreach ($attachments as $att) {
    echo "ID: {$att->id}, Path: {$att->chemin_fichier}, Exists: " . (file_exists(storage_path('app/public/'.$att->chemin_fichier)) ? 'Yes' : 'No') . "\n";
}
