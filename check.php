<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$dirs = glob(base_path('Modules/*/app/Filament/Resources'));
print_r($dirs);

var_dump(class_exists('Modules\Fornecedores\Filament\Resources\FornecedorResource'));
