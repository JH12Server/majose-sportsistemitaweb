<?php

// Script para eliminar pedidos de prueba (order_number LIKE 'TEST-%')
// Ejecútalo con: php scripts/delete_test_orders.php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use Illuminate\Support\Facades\Log;

$deleted = Order::where('order_number', 'like', 'TEST-%')->get();

if ($deleted->isEmpty()) {
    echo "No se encontraron pedidos de prueba (TEST-*)\n";
    exit(0);
}

foreach ($deleted as $order) {
    $id = $order->id;
    $num = $order->order_number;
    // opcional: eliminar archivos relacionados
    // eliminar items y archivos si existen (rely on cascade if FK with onDelete)
    try {
        $order->delete();
        echo "Eliminado pedido: {$num} (ID: {$id})\n";
    } catch (\Throwable $e) {
        echo "Error al eliminar pedido {$num} (ID: {$id}): " . $e->getMessage() . "\n";
    }
}

echo "Proceso finalizado.\n";
