<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Setting;

class ConfiguracoesEmpresa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $title = 'Configurações da empresa';
    protected static \UnitEnum|string|null $navigationGroup = 'Visão Geral da Empresa';
    protected static ?int $navigationSort = 1;
    
    protected string $view = 'comercial::filament.pages.configuracoes-empresa';

    public ?array $data = [];

    public function mount(): void
    {
        $logo = Setting::where('key', 'company_logo')->first();
        $this->form->fill([
            'company_logo' => $logo ? $logo->value : null,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Identidade Visual')
                    ->description('Atualize a logo da sua empresa. Ela será exibida no menu lateral.')
                    ->schema([
                        FileUpload::make('company_logo')
                            ->label('Logo da Empresa')
                            ->image()
                            ->directory('logos')
                            ->maxSize(2048)
                            ->imagePreviewHeight('150')
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::updateOrCreate(
            ['key' => 'company_logo'],
            ['value' => $data['company_logo']]
        );

        Notification::make()
            ->title('Configurações salvas com sucesso!')
            ->success()
            ->send();
            
        // Trigger a frontend reload/redirect to see the new logo in the sidebar
        $this->redirect(request()->header('Referer'));
    }
}
