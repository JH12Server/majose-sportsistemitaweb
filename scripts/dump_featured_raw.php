<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('products')->where('featured', true)->take(12)->get(['id','name','images','main_image']);
foreach ($rows as $r) {
    echo "$r->id | $r->name | images:" . ($r->images ?? 'NULL') . " | main_image:" . ($r->main_image ?? 'NULL') . "\n";
}
