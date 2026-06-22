<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PreferenciasPessoais extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $title = 'Preferências pessoais';
    protected static \UnitEnum|string|null $navigationGroup = 'Minha Conta';
    protected static ?int $navigationSort = 1;
    
    protected string $view = 'comercial::filament.pages.preferencias-pessoais';

    public $name;
    public $email;
    
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $logout_other_devices = true;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name ?? $user->razao_social ?? 'Test User';
        $this->email = $user->email;
    }

    public function salvarGeral()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        // If the model uses 'razao_social' instead of 'name'
        if (isset($user->razao_social)) {
            $user->razao_social = $this->name;
        }
        $user->save();

        \Filament\Notifications\Notification::make()
            ->title('Dados atualizados com sucesso!')
            ->success()
            ->send();
    }

    public function alterarSenha()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::min(8), 'confirmed'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($this->new_password);
        $user->save();

        if ($this->logout_other_devices) {
            auth()->logoutOtherDevices($this->new_password);
        }

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        \Filament\Notifications\Notification::make()
            ->title('Senha alterada com sucesso!')
            ->success()
            ->send();
    }
}
