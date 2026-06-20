<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$produtos = \Modules\Comercial\Models\OportunidadeProduto::all();
echo "Pivot Count: " . $produtos->count() . "\n";
if ($produtos->count() > 0) {
    echo "First Pivot Qty: " . $produtos->first()->quantidade . "\n";
    echo "First Pivot Price: " . $produtos->first()->preco_unitario . "\n";
}
