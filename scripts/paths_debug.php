<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "storage_path: " . storage_path() . PHP_EOL;
echo "storage_app_public: " . storage_path('app/public') . PHP_EOL;
echo "storage_products: " . storage_path('app/public/products') . PHP_EOL;
echo "public_path: " . public_path() . PHP_EOL;
echo "public_storage: " . public_path('storage') . PHP_EOL;
echo "public_storage_products: " . public_path('storage/products') . PHP_EOL;

$paths = [storage_path('app/public/products'), public_path('storage/products')];
foreach ($paths as $p) {
    echo "$p exists? " . (file_exists($p) ? 'YES' : 'NO') . PHP_EOL;
    if (file_exists($p)) {
        echo "is_dir? " . (is_dir($p) ? 'YES' : 'NO') . PHP_EOL;
        echo "is_link? " . (is_link($p) ? 'YES' : 'NO') . PHP_EOL;
        if (is_dir($p)) {
            $files = scandir($p);
            foreach ($files as $f) { if ($f === '.'||$f==='..') continue; echo " - $f\n"; }
        }
    }
}
