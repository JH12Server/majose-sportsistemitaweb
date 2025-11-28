<?php

use App\Models\Product;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$storageBase = storage_path('app/public');
$publicBase = public_path('storage');

echo "Removing broken image references from products...\n";

$products = Product::all();
$fixedCount = 0;
$removedImageRefs = 0;
$fixedMain = 0;

foreach ($products as $product) {
    $changed = false;
    $images = $product->images ?? [];
    $newImages = [];
    foreach ($images as $img) {
        $path = ltrim($img, '/\\');
        $storagePath = $storageBase . DIRECTORY_SEPARATOR . $path;
        $publicPath = $publicBase . DIRECTORY_SEPARATOR . $path;
        if (file_exists($storagePath) || file_exists($publicPath)) {
            $newImages[] = $path;
        } else {
            $removedImageRefs++;
            $changed = true;
            echo "Removing broken image reference for product {$product->id}: {$img}\n";
        }
    }

    // Check main image
    $main = $product->main_image;
    $mainPath = $main ? ltrim($main, '/\\') : null;
    $mainExists = false;
    if ($mainPath) {
        if (file_exists($storageBase . DIRECTORY_SEPARATOR . $mainPath) || file_exists($publicBase . DIRECTORY_SEPARATOR . $mainPath)) {
            $mainExists = true;
        }
    }

    if (! $mainExists) {
        // set main to first existing image or null
        if (!empty($newImages)) {
            $product->main_image = $newImages[0];
            $fixedMain++;
            $changed = true;
            echo "Fixed main_image for product {$product->id} to {$newImages[0]}\n";
        } else {
            if ($product->main_image !== null) {
                $product->main_image = null;
                $fixedMain++;
                $changed = true;
                echo "Cleared main_image for product {$product->id} (no valid images)\n";
            }
        }
    }

    if ($changed) {
        $product->images = array_values(array_unique($newImages));
        $product->save();
        $fixedCount++;
    }
}

echo "\nSummary:\n";
echo "Products fixed: {$fixedCount}\n";
echo "Image refs removed: {$removedImageRefs}\n";
echo "Main images fixed/cleared: {$fixedMain}\n";

echo "Done. Run php scripts/check_public_images.php to verify.\n";

exit(0);
