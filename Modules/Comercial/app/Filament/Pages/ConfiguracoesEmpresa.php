<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;

class ConfiguracoesEmpresa extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $title = 'Configurações da empresa';
    protected static \UnitEnum|string|null $navigationGroup = 'Visão Geral da Empresa';
    protected static ?int $navigationSort = 1;
    
    protected string $view = 'comercial::filament.pages.configuracoes-empresa';
}
