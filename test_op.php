<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$op = \Modules\Comercial\Models\Oportunidade::orderBy('id', 'desc')->first();
$total1 = $op->oportunidadeProdutos()->sum(\Illuminate\Support\Facades\DB::raw('quantidade * preco_unitario'));
echo "Total 1: " . $total1 . "\n";
