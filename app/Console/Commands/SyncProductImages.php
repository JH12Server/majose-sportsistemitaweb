<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncProductImages extends Command
{
    protected $signature = 'products:sync-images';
    protected $description = 'Sync images from storage/app/public/products to public/storage/products and run cleanup';

    public function handle()
    {
        $this->info('Starting sync of product images...');

        $storageDir = storage_path('app/public/products');
        $publicDir = public_path('storage/products');

        if (!is_dir($storageDir)) {
            $this->error("Storage products directory does not exist: {$storageDir}");
            return Command::FAILURE;
        }

        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }

        // Copy all files from storage to public (overwrite)
        $files = scandir($storageDir);
        $copied = 0;
        foreach ($files as $f) {
            if ($f === '.' || $f === '..') continue;
            $src = $storageDir . DIRECTORY_SEPARATOR . $f;
            $dst = $publicDir . DIRECTORY_SEPARATOR . $f;
            if (is_file($src)) {
                if (!@copy($src, $dst)) {
                    $this->warn("Failed to copy {$src} to {$dst}");
                } else {
                    $copied++;
                }
            }
        }

        $this->info("Copied {$copied} files to public storage");

        // Remove orphan files in public that are not in storage
        $publicFiles = scandir($publicDir);
        $removed = 0;
        foreach ($publicFiles as $f) {
            if ($f === '.' || $f === '..') continue;
            $publicPath = $publicDir . DIRECTORY_SEPARATOR . $f;
            $storagePath = $storageDir . DIRECTORY_SEPARATOR . $f;
            if (is_file($publicPath) && !file_exists($storagePath)) {
                @unlink($publicPath);
                $removed++;
            }
        }

        $this->info("Removed {$removed} orphan files from public storage");

        // Run additional cleanup scripts if present
        $base = base_path();
        $scripts = [
            $base . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'remove_broken_product_image_entries.php',
            $base . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'dedupe_product_images.php',
        ];
        foreach ($scripts as $script) {
            if (file_exists($script)) {
                $this->info("Running cleanup script: {$script}");
                $output = null;
                $return = null;
                exec("php " . escapeshellarg($script) . " 2>&1", $output, $return);
                foreach ($output as $line) { $this->line($line); }
                $this->info("Script exit code: {$return}");
            }
        }

        // Clear caches so views pick up changes
        $this->call('view:clear');
        $this->call('cache:clear');
        $this->call('route:clear');

        $this->info('Product image sync completed.');
        return Command::SUCCESS;
    }
}
