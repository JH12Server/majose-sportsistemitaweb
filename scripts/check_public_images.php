<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::where('featured', true)->get(['id','name','main_image','images']);
foreach ($products as $p) {
    $path = $p->main_image ?: ($p->images[0] ?? null);
    $exists = $path ? file_exists(public_path('storage/' . $path)) : false;
    echo sprintf("%d | %s | %s | %s\n", $p->id, $p->name, $path ?? 'NULL', $exists ? 'OK' : 'MISSING');
}
