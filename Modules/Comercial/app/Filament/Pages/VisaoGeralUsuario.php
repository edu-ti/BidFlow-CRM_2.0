<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;

class VisaoGeralUsuario extends Page
{
    protected static ?string $title = 'Eduardo Cabral (você)';
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static \UnitEnum|string|null $navigationGroup = 'Visão Geral da Empresa';
    protected static ?string $navigationLabel = 'Visão geral do usuário';
    protected static ?int $navigationSort = 3;
    
    protected string $view = 'comercial::filament.pages.visao-geral-usuario';

    public function mount()
    {
        // Add Breadcrumbs so the user can easily go back to "Usuários e acesso"
    }

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return view('comercial::filament.pages.visao-geral-usuario-heading');
    }

    public function getBreadcrumbs(): array
    {
        return [
            url('/admin/usuarios-acessos') => 'Usuários e acesso',
            '' => 'Visão geral',
        ];
    }
}
