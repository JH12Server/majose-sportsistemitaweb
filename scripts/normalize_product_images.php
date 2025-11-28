<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$exts = ['png','jpg','jpeg','gif','webp'];
$pattern = '/(' . implode('|', $exts) . ')$/i';

$products = Product::all();
$changed = 0;
foreach ($products as $p) {
    $images = $p->images ?? [];
    $normalized = [];
    foreach ($images as $img) {
        $img = (string)$img;
        // if path contains products/ and missing a dot before extension, fix it
        // e.g. products/delantal-de-cocinapng -> products/delantal-de-cocina.png
        foreach ($exts as $ext) {
            if (preg_match('/products\/.*' . $ext . '$/i', $img) && !preg_match('/\.' . $ext . '$/i', $img)) {
                // insert dot before extension
                $img = preg_replace('/(' . $ext . ')$/i', '.$1', $img);
                break;
            }
        }
        // remove duplicate slashes
        $img = preg_replace('#/+#','/', $img);
        $normalized[] = $img;
    }
    $normalized = array_values(array_unique($normalized));
    if ($normalized != ($p->images ?? [])) {
        $p->images = $normalized;
        // ensure main_image consistent
        $p->main_image = count($normalized) ? $normalized[0] : null;
        $p->save();
        $changed++;
    }
}

echo "Normalized images for $changed products\n";
