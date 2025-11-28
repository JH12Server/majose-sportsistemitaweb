<?php

use Illuminate\Support\Str;
use App\Models\Product;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$storageDir = storage_path('app/public/products');
$publicDir = public_path('storage/products');

echo "Starting cleanup of product images...\n";

$allFiles = [];
if (is_dir($storageDir)) {
    foreach (scandir($storageDir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $allFiles[$f] = true;
    }
}
if (is_dir($publicDir)) {
    foreach (scandir($publicDir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $allFiles[$f] = true;
    }
}

$allFiles = array_keys($allFiles);
if (empty($allFiles)) {
    echo "No product files found in storage or public storage to process.\n";
    exit(0);
}

$renamed = 0;
$deleted = 0;
$updatedDb = 0;
$skipped = 0;

foreach ($allFiles as $file) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $base = pathinfo($file, PATHINFO_FILENAME);
    $slugged = Str::slug($base) . ($ext ? '.' . $ext : '');

    if ($file === $slugged) {
        // already slugged
        continue;
    }

    $storageOld = $storageDir . DIRECTORY_SEPARATOR . $file;
    $storageNew = $storageDir . DIRECTORY_SEPARATOR . $slugged;
    $publicOld = $publicDir . DIRECTORY_SEPARATOR . $file;
    $publicNew = $publicDir . DIRECTORY_SEPARATOR . $slugged;

    $storageOldExists = file_exists($storageOld);
    $storageNewExists = file_exists($storageNew);
    $publicOldExists = file_exists($publicOld);
    $publicNewExists = file_exists($publicNew);

    // If slugged already exists, remove the old (non-slug) duplicates
    if (($storageOldExists || $publicOldExists) && ($storageNewExists || $publicNewExists)) {
        if ($storageOldExists) { @unlink($storageOld); }
        if ($publicOldExists) { @unlink($publicOld); }
        $deleted++;
        echo "Deleted duplicate files for: {$file} -> kept {$slugged}\n";

        // Update DB entries that point to old path to use slugged path
        $oldPath = 'products/' . $file;
        $newPath = 'products/' . $slugged;

        // main_image
        $affected = Product::where('main_image', $oldPath)->update(['main_image' => $newPath]);
        if ($affected) {
            $updatedDb += $affected;
            echo "Updated main_image for {$affected} products: {$oldPath} -> {$newPath}\n";
        }

        // images array
        $products = Product::whereJsonContains('images', $oldPath)->get();
        foreach ($products as $p) {
            $images = $p->images ?? [];
            $changed = false;
            foreach ($images as &$img) {
                if ($img === $oldPath) { $img = $newPath; $changed = true; }
            }
            if ($changed) { $p->images = array_values(array_unique($images)); $p->save(); $updatedDb++; }

        }

        continue;
    }

    // If slugged does not exist but old exists, rename/move it
    if (!$storageNewExists && $storageOldExists) {
        if (@rename($storageOld, $storageNew)) {
            $renamed++;
            echo "Renamed in storage: {$file} -> {$slugged}\n";

            // also try to rename in public dir if present
            if ($publicOldExists && ! $publicNewExists) {
                @rename($publicOld, $publicNew);
            }

            // Update DB references
            $oldPath = 'products/' . $file;
            $newPath = 'products/' . $slugged;
            $affected = Product::where('main_image', $oldPath)->update(['main_image' => $newPath]);
            if ($affected) { $updatedDb += $affected; echo "Updated main_image for {$affected} products: {$oldPath} -> {$newPath}\n"; }
            $products = Product::whereJsonContains('images', $oldPath)->get();
            foreach ($products as $p) {
                $images = $p->images ?? [];
                $changed = false;
                foreach ($images as &$img) {
                    if ($img === $oldPath) { $img = $newPath; $changed = true; }
                }
                if ($changed) { $p->images = array_values(array_unique($images)); $p->save(); $updatedDb++; }
            }

            continue;
        } else {
            echo "Failed to rename {$storageOld} -> {$storageNew}\n";
            $skipped++;
            continue;
        }
    }

    // If slugged does not exist but only publicOld exists, move it to both places if possible
    if (!$storageNewExists && !$storageOldExists && $publicOldExists) {
        // try to copy from public to storage
        if (!is_dir($storageDir)) { @mkdir($storageDir, 0755, true); }
        if (@copy($publicOld, $storageNew)) {
            $renamed++;
            echo "Copied public {$file} -> storage {$slugged}\n";
            // update DB
            $oldPath = 'products/' . $file;
            $newPath = 'products/' . $slugged;
            $affected = Product::where('main_image', $oldPath)->update(['main_image' => $newPath]);
            if ($affected) { $updatedDb += $affected; echo "Updated main_image for {$affected} products: {$oldPath} -> {$newPath}\n"; }
            $products = Product::whereJsonContains('images', $oldPath)->get();
            foreach ($products as $p) {
                $images = $p->images ?? [];
                $changed = false;
                foreach ($images as &$img) {
                    if ($img === $oldPath) { $img = $newPath; $changed = true; }
                }
                if ($changed) { $p->images = array_values(array_unique($images)); $p->save(); $updatedDb++; }
            }
            // optionally remove the old public file
            @unlink($publicOld);
            continue;
        } else {
            echo "Failed to copy public {$publicOld} to storage {$storageNew}\n";
            $skipped++;
            continue;
        }
    }

}

echo "\nSummary:\n";
echo "Renamed/moved: {$renamed}\n";
echo "Deleted duplicates: {$deleted}\n";
echo "DB entries updated: {$updatedDb}\n";
echo "Skipped/failures: {$skipped}\n";

echo "Cleanup finished. Run php scripts/check_public_images.php to verify.\n";

exit(0);
