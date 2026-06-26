<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$logo = \App\Models\Setting::where('key', 'company_logo')->first();
$val = $logo ? $logo->value : 'NOT_FOUND';
echo "LOGO_VALUE=" . json_encode($val) . "\n";
