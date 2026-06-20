<?php
$ops = \Modules\Comercial\Models\Oportunidade::all()->pluck('status');
echo "Status in DB: \n";
foreach($ops as $op) {
    echo $op . "\n";
}
