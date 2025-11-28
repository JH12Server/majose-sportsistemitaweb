<?php

use App\Models\Product;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$storageDir = storage_path('app/public/products');
$publicDir = public_path('storage/products');

echo "Starting dedupe by content for product images...\n";

$files = [];
foreach ([$storageDir, $publicDir] as $dir) {
    if (!is_dir($dir)) continue;
    $items = scandir($dir);
    foreach ($items as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $f;
        if (!is_file($full)) continue;
        $hash = md5_file($full);
        $files[$hash][] = ['name' => $f, 'full' => $full, 'dir' => $dir];
    }
}

$kept = 0;
$removed = 0;
$updatedDb = 0;

foreach ($files as $hash => $group) {
    if (count($group) <= 1) continue; // no duplicates

    // Choose preferred: prefer slugged filename (no spaces), prefer file in storageDir
    usort($group, function($a, $b) use ($storageDir) {
        $aSlug = preg_replace('/[^a-z0-9\-_\.]/i','', $a['name']) === $a['name'] ? 0 : 1;
        $bSlug = preg_replace('/[^a-z0-9\-_\.]/i','', $b['name']) === $b['name'] ? 0 : 1;
        if ($aSlug !== $bSlug) return $aSlug - $bSlug;
        $aIsStorage = strpos($a['dir'], $storageDir) === 0 ? 0 : 1;
        $bIsStorage = strpos($b['dir'], $storageDir) === 0 ? 0 : 1;
        if ($aIsStorage !== $bIsStorage) return $aIsStorage - $bIsStorage;
        return strcmp($a['name'], $b['name']);
    });

    $preferred = $group[0];
    $preferredPath = 'products/' . $preferred['name'];
    $kept++;

    // For the rest, update DB references and delete files
    for ($i = 1; $i < count($group); $i++) {
        $item = $group[$i];
        $oldPath = 'products/' . $item['name'];
        $newPath = $preferredPath;

        // Update main_image
        $affected = Product::where('main_image', $oldPath)->update(['main_image' => $newPath]);
        if ($affected) { $updatedDb += $affected; echo "Updated main_image: {$oldPath} -> {$newPath} ({$affected} rows)\n"; }

        // Update images arrays
        $products = Product::whereJsonContains('images', $oldPath)->get();
        foreach ($products as $p) {
            $images = $p->images ?? [];
            $changed = false;
            foreach ($images as &$img) {
                if ($img === $oldPath) { $img = $newPath; $changed = true; }
            }
            if ($changed) {
                $p->images = array_values(array_unique($images));
                $p->save();
                $updatedDb++;
                echo "Updated images array for product {$p->id}: replaced {$oldPath} -> {$newPath}\n";
            }
        }

        // Remove file
        if (file_exists($item['full'])) {
            if (@unlink($item['full'])) {
                $removed++;
                echo "Removed duplicate file: {$item['full']}\n";
            } else {
                echo "Failed to remove file: {$item['full']}\n";
            }
        }
    }
}

echo "\nSummary:\n";
echo "Kept groups: {$kept}\n";
echo "Files removed: {$removed}\n";
echo "DB updates: {$updatedDb}\n";

echo "Done. Run php scripts/check_public_images.php to verify.\n";
exit(0);
