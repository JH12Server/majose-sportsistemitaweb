<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::all();
if ($products->isEmpty()) {
    echo "No products found.\n";
    exit(0);
}

foreach ($products as $p) {
    $main = $p->main_image;
    $url = $p->main_image_url;
    $storagePath = storage_path('app/public/' . ltrim((string)$main, '/\\'));
    $publicPath = public_path('storage/' . ltrim((string)$main, '/\\'));
    echo "ID: {$p->id} | Name: {$p->name}\n";
    echo " main_image: ".($main ?? 'NULL')."\n";
    echo " main_image_url: {$url}\n";
    echo " exists in storage/app/public: ".(file_exists($storagePath) ? 'YES' : 'NO')." -> {$storagePath}\n";
    echo " exists in public/storage: ".(file_exists($publicPath) ? 'YES' : 'NO')." -> {$publicPath}\n";
    echo str_repeat('-', 60) . "\n";
}

exit(0);
