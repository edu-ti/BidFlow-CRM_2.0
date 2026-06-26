<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Components\Section;

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
        $keys = [
            'company_logo', 'razao_social', 'nome_fantasia', 'cnpj', 
            'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
            'telefone', 'celular', 'email', 'site', 'meta_global'
        ];
        
        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key')->toArray();
        
        $this->form->fill([
            'company_logo' => $settings['company_logo'] ?? null,
            'razao_social' => $settings['razao_social'] ?? null,
            'nome_fantasia' => $settings['nome_fantasia'] ?? null,
            'cnpj' => $settings['cnpj'] ?? null,
            'cep' => $settings['cep'] ?? null,
            'logradouro' => $settings['logradouro'] ?? null,
            'numero' => $settings['numero'] ?? null,
            'complemento' => $settings['complemento'] ?? null,
            'bairro' => $settings['bairro'] ?? null,
            'cidade' => $settings['cidade'] ?? null,
            'uf' => $settings['uf'] ?? null,
            'telefone' => $settings['telefone'] ?? null,
            'celular' => $settings['celular'] ?? null,
            'email' => $settings['email'] ?? null,
            'site' => $settings['site'] ?? null,
            'meta_global' => $settings['meta_global'] ?? 200000,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Tabs::make('Configuracoes')
                    ->tabs([
                        Tab::make('Dados Fiscais e Logo')
                            ->schema([
                                FileUpload::make('company_logo')
                                    ->label('Logo da Empresa')
                                    ->image()
                                    ->disk('public')
                                    ->directory('logos')
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('150')
                                    ->columnSpanFull(),
                                
                                Section::make('Dados da Empresa')->schema([
                                    TextInput::make('razao_social')
                                        ->label('Razão Social')
                                        ->columnSpan(2),
                                    TextInput::make('nome_fantasia')
                                        ->label('Nome Fantasia')
                                        ->columnSpan(2),
                                    TextInput::make('cnpj')
                                        ->label('CNPJ')
                                        ->mask('99.999.999/9999-99')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $cnpj = preg_replace('/[^0-9]/', '', $state);
                                            if (strlen($cnpj) === 14) {
                                                $response = Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
                                                if ($response->successful()) {
                                                    $data = $response->json();
                                                    $set('razao_social', $data['razao_social'] ?? null);
                                                    $set('nome_fantasia', $data['nome_fantasia'] ?? $data['razao_social'] ?? null);
                                                    
                                                    if (!empty($data['cep'])) {
                                                        $set('cep', preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $data['cep']));
                                                        $set('logradouro', $data['logradouro'] ?? null);
                                                        $set('numero', $data['numero'] ?? null);
                                                        $set('complemento', $data['complemento'] ?? null);
                                                        $set('bairro', $data['bairro'] ?? null);
                                                        $set('cidade', $data['municipio'] ?? null);
                                                        $set('uf', $data['uf'] ?? null);
                                                    }
                                                    
                                                    $set('telefone', $data['ddd_telefone_1'] ?? null);
                                                    $set('email', $data['email'] ?? null);
                                                }
                                            }
                                        })
                                        ->columnSpan(1),
                                ])->columns(5),
                                
                                Section::make('Endereço')->schema([
                                    TextInput::make('cep')
                                        ->label('CEP')
                                        ->mask('99999-999')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            $cep = preg_replace('/[^0-9]/', '', $state);
                                            if (strlen($cep) === 8) {
                                                $response = Http::get("https://viacep.com.br/ws/{$cep}/json/")->json();
                                                if (!isset($response['erro'])) {
                                                    $set('logradouro', $response['logradouro'] ?? null);
                                                    $set('bairro', $response['bairro'] ?? null);
                                                    $set('cidade', $response['localidade'] ?? null);
                                                    $set('uf', $response['uf'] ?? null);
                                                }
                                            }
                                        })
                                        ->columnSpan(1),
                                    TextInput::make('logradouro')
                                        ->label('Endereço (Logradouro)')
                                        ->columnSpan(3),
                                    TextInput::make('numero')
                                        ->label('Número')
                                        ->columnSpan(1),
                                    TextInput::make('complemento')
                                        ->label('Complemento')
                                        ->columnSpan(1),
                                    TextInput::make('bairro')
                                        ->label('Bairro')
                                        ->columnSpan(2),
                                    TextInput::make('cidade')
                                        ->label('Cidade')
                                        ->columnSpan(1),
                                    TextInput::make('uf')
                                        ->label('UF')
                                        ->columnSpan(1),
                                ])->columns(5),

                                Section::make('Contato')->schema([
                                    TextInput::make('telefone')
                                        ->label('Fone')
                                        ->mask('(99) 9999-9999')
                                        ->columnSpan(1),
                                    TextInput::make('celular')
                                        ->label('Celular')
                                        ->mask('(99) 99999-9999')
                                        ->columnSpan(1),
                                    TextInput::make('email')
                                        ->label('E-mail')
                                        ->email()
                                        ->columnSpan(1),
                                    TextInput::make('site')
                                        ->label('Site')
                                        ->url()
                                        ->columnSpan(1),
                                ])->columns(4),

                                Section::make('Metas')->schema([
                                    TextInput::make('meta_global')
                                        ->label('Meta Global de Vendas (R$)')
                                        ->numeric()
                                        ->default(200000)
                                        ->columnSpanFull(),
                                ]),
                            ]),
                            
                        Tab::make('Matriz de Metas e Comissões')
                            ->schema([
                                \Filament\Schemas\Components\View::make('filament.components.metas-matriz-wrapper')
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {
            $settingKeys = [
                'company_logo', 'razao_social', 'nome_fantasia', 'cnpj', 
                'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf',
                'telefone', 'celular', 'email', 'site', 'meta_global'
            ];
            
            foreach ($settingKeys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::updateOrCreate(['key' => $key], ['value' => $data[$key]]);
                }
            }
        });

        Notification::make()
            ->title('Configurações da empresa salvas com sucesso!')
            ->success()
            ->send();
    }
}
