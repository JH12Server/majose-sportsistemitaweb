<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaginaWebController extends Controller
{
    protected $pagePath;

    public function __construct()
    {
        // Ruta a la carpeta de la página web
        $this->pagePath = 'e:\\PPI\\PaginamajoseSport';
    }

    public function editor()
    {
        // Obtener lista de archivos HTML
        $files = [];
        
        if (File::isDirectory($this->pagePath)) {
            $allFiles = File::files($this->pagePath);
            
            foreach ($allFiles as $file) {
                if ($file->getExtension() === 'html' || $file->getExtension() === 'css' || $file->getExtension() === 'js') {
                    $files[] = [
                        'name' => $file->getFilename(),
                        'path' => $file->getRealPath(),
                        'type' => $file->getExtension()
                    ];
                }
            }
        }

        return view('admin.pagina-web-editor', compact('files'));
    }

    public function getFile(Request $request)
    {
        $fileName = $request->input('file');
        $filePath = $this->pagePath . '\\' . $fileName;

        // Validar que el archivo esté dentro del directorio permitido
        if (!File::isFile($filePath) || strpos(realpath($filePath), realpath($this->pagePath)) !== 0) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        $content = File::get($filePath);
        
        return response()->json([
            'content' => $content,
            'file' => $fileName,
            'type' => File::extension($filePath)
        ]);
    }

    public function saveFile(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|string',
            'content' => 'required|string'
        ]);

        $fileName = $validated['file'];
        $filePath = $this->pagePath . '\\' . $fileName;

        // Validar que el archivo esté dentro del directorio permitido
        if (!File::isFile($filePath) || strpos(realpath($filePath), realpath($this->pagePath)) !== 0) {
            return response()->json(['error' => 'Archivo no encontrado'], 404);
        }

        // Guardar el archivo
        File::put($filePath, $validated['content']);

        return response()->json([
            'success' => true,
            'message' => 'Archivo guardado correctamente'
        ]);
    }

    public function preview()
    {
        $indexPath = $this->pagePath . '\\index.html';

        if (!File::isFile($indexPath)) {
            return response()->view('errors.404', [], 404);
        }

        $content = File::get($indexPath);
        
        // Retornar el HTML sin layout
        return response($content)->header('Content-Type', 'text/html; charset=utf-8');
    }
}
