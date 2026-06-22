<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;

class UsuariosAcesso extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $title = 'Usuários e acesso';
    protected static \UnitEnum|string|null $navigationGroup = 'Visão Geral da Empresa';
    protected static ?string $navigationLabel = 'Gerenciar usuários';
    protected static ?int $navigationSort = 2;
    
    protected string $view = 'comercial::filament.pages.usuarios-acesso';

    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    public $activeTab = 'usuarios';
    
    // Modals visibility
    public $showPermissionModal = false;
    public $showVisibilityModal = false;
    public $showTeamModal = false;

    // Form inputs
    public $permName;
    public $permDesc;
    public $permProduct = 'Deals';
    
    public $visName;
    public $visDesc;
    public $visParent = 'Nenhum';
    
    public $teamName;
    public $teamManager;
    public $teamDesc;

    public function mount()
    {
        //
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function savePermission()
    {
        $this->showPermissionModal = false;
        \Filament\Notifications\Notification::make()->title('Conjunto de permissões salvo!')->success()->send();
    }

    public function saveVisibility()
    {
        $this->showVisibilityModal = false;
        \Filament\Notifications\Notification::make()->title('Grupo de visibilidade salvo!')->success()->send();
    }

    public function saveTeam()
    {
        $this->showTeamModal = false;
        \Filament\Notifications\Notification::make()->title('Equipe salva!')->success()->send();
    }
}
