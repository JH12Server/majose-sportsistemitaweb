<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$products = Product::where('featured', true)->take(12)->get(['id','name','main_image']);
foreach ($products as $p) {
    echo "$p->id | $p->name | $p->main_image\n";
}
