<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PreferenciasPessoais extends Page
{
    use WithFileUploads;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-face-smile';
    protected static ?string $title = 'Preferências pessoais';
    protected static \UnitEnum|string|null $navigationGroup = 'Minha Conta';
    protected static ?int $navigationSort = 1;
    
    protected string $view = 'comercial::filament.pages.preferencias-pessoais';

    public $name;
    public $email;
    public $telefone;
    public $celular;
    public $cargo_funcao;
    public $photo;
    
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $logout_other_devices = true;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name ?? $user->razao_social ?? 'Test User';
        $this->email = $user->email;
        $this->telefone = $user->telefone ?? '';
        $this->celular = $user->celular ?? '';
        $this->cargo_funcao = $user->cargo_funcao ?? '';
    }

    public function salvarGeral()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:255',
            'cargo_funcao' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $user->name = $this->name;
        $user->telefone = $this->telefone;
        $user->celular = $this->celular;
        $user->cargo_funcao = $this->cargo_funcao;
        
        if ($this->photo) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }
            $user->avatar_url = $this->photo->store('avatars', 'public');
        }

        // If the model uses 'razao_social' instead of 'name'
        if (isset($user->razao_social)) {
            $user->razao_social = $this->name;
        }
        $user->save();

        \Filament\Notifications\Notification::make()
            ->title('Dados atualizados com sucesso!')
            ->success()
            ->send();
            
        $this->photo = null;
    }

    public function apagarFoto()
    {
        $user = auth()->user();
        if ($user->avatar_url) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar_url);
            $user->avatar_url = null;
            $user->save();
        }
        
        \Filament\Notifications\Notification::make()
            ->title('Foto apagada com sucesso!')
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
