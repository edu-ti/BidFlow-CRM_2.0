<?php
require vendor/autoload.php;
 = get_declared_classes();
 = array_filter(, function() {
    return strpos(, Filament\Tables\Actions) !== false;
});
print_r();
