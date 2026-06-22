<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class MainDashboard extends BaseDashboard
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static ?int $navigationSort = -100;
    
    protected string $view = 'filament.pages.main-dashboard';

    public function getHeading(): string | Htmlable
    {
        // Retornamos vazio para que possamos desenhar o cabeçalho 100% customizado na view
        return '';
    }
}
