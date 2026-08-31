<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

DB::table('migrations')->truncate();

$migrationFiles = File::files(database_path('migrations'));
foreach ($migrationFiles as $file) {
    $name = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    DB::table('migrations')->insert([
        'migration' => $name,
        'batch'     => 1,
    ]);
}

echo "Migrations table synced with " . count($migrationFiles) . " files.\n";
