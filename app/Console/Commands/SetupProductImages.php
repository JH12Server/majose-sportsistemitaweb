<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\File as HttpFile;

class SetupProductImages extends Command
{
    protected $signature = 'products:setup-images';
    protected $description = 'Set up product images from the public directory to storage and update database';

    public function handle()
    {
        $this->info('Starting product images setup...');

        // Ensure the products directory exists on the public disk
        Storage::disk('public')->makeDirectory('products');

        // Map of product names to image filenames
        $productImages = [
            'Bolsa Tote Personalizada' => 'Tote bag.png',
            'Camiseta Personalizada' => 'camisa sublimada.png',
            'Chaqueta con Bordado' => 'chaqueta con bordado.png',
            'Delantal de Cocina' => 'delantal de cocina.png',
            'Gorra Bordada' => 'gorra bordada.png',
            'Mochila Bordada' => 'mochila bordada.png',
            'Polo Empresarial' => 'polo empresarial.png',
            'Toalla Personalizada' => 'toalla personalizada.png',
        ];

        $bar = $this->output->createProgressBar(count($productImages));
        $bar->start();

        foreach ($productImages as $productName => $imageName) {
            $sourcePath = public_path("assets/img/Catalogo imagenes/{$imageName}");
            $ext = pathinfo($imageName, PATHINFO_EXTENSION);
            $base = pathinfo($imageName, PATHINFO_FILENAME);
            $filename = Str::slug($base) . '.' . ($ext ?: 'png');
            $destinationPath = 'products/' . $filename;

            if (file_exists($sourcePath)) {
                // Try to copy the file to the public disk (storage/app/public/products/...)
                $written = false;
                try {
                    Storage::disk('public')->putFileAs('products', new HttpFile($sourcePath), $filename);
                    if (Storage::disk('public')->exists($destinationPath)) {
                        $written = true;
                    }
                } catch (\Throwable $e) {
                    $this->warn("Warning writing to storage disk: " . $e->getMessage());
                }

                // Fallback: ensure `public/storage/products` exists and copy file there
                if (! $written) {
                    $publicStorageDir = public_path('storage/products');
                    if (!is_dir($publicStorageDir)) {
                        @mkdir($publicStorageDir, 0755, true);
                    }
                    $copied = @copy($sourcePath, public_path('storage/' . $destinationPath));
                    if (! $copied) {
                        $this->error("Failed to copy file to public storage: {$sourcePath} -> " . public_path('storage/' . $destinationPath));
                    } else {
                        $written = true;
                    }
                }
                
                // Update the product in the database
                $product = Product::where('name', 'like', "%{$productName}%")->first();
                
                if ($product) {
                    // Actualizar tanto main_image como el array de imágenes
                    $images = $product->images ?? [];
                    if (!in_array($destinationPath, $images)) {
                        array_unshift($images, $destinationPath);
                    }
                    // Ensure images saved and mark as featured so they show in the customer view
                    $product->main_image = $destinationPath;
                    $product->images = $images;
                    $product->featured = true;
                    $product->save();
                    
                    $this->info("\nImagen actualizada para: {$productName}");
                } else {
                    $this->warn("\nProduct not found: {$productName}");
                }
            } else {
                $this->error("\nImage not found: {$sourcePath}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n\nProduct images setup completed!");
        
        // Create symbolic link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            $this->call('storage:link');
        }

        return Command::SUCCESS;
    }
}
