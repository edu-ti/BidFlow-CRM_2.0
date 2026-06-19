<?php

namespace Modules\Fornecedores\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Fornecedores\Models\Fornecedor;
use Modules\Fornecedores\Filament\Resources\FornecedorResource\Pages;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static \UnitEnum|string|null $navigationGroup = 'Cadastros';

    protected static ?string $modelLabel = 'Fornecedor/Cliente';

    protected static ?string $pluralModelLabel = 'Fornecedores/Clientes';

    protected static ?string $slug = 'fornecedores';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Tabs::make('Tabs')
                    ->tabs([
                        \Filament\Schemas\Components\Tabs\Tab::make('Dados Principais')
                            ->schema([
                                Forms\Components\Select::make('tipo_pessoa')
                                    ->label('Tipo de Pessoa')
                                    ->options([
                                        'PJ' => 'Pessoa Jurídica (CNPJ)',
                                        'PF' => 'Pessoa Física (CPF)',
                                    ])
                                    ->default('PJ')
                                    ->live()
                                    ->required(),
                                
                                Forms\Components\TextInput::make('cpf_cnpj')
                                    ->label(fn ($get) => $get('tipo_pessoa') === 'PF' ? 'CPF' : 'CNPJ')
                                    ->mask(fn ($get) => $get('tipo_pessoa') === 'PF' ? '999.999.999-99' : '99.999.999/9999-99')
                                    ->maxLength(18)
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, $set, $get) {
                                        if (!$state || $get('tipo_pessoa') !== 'PJ') return;
                                        
                                        $cnpj = preg_replace('/[^0-9]/', '', $state);
                                        if (strlen($cnpj) !== 14) return;
                                        
                                        try {
                                            $response = \Illuminate\Support\Facades\Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
                                            if ($response->successful()) {
                                                $data = $response->json();
                                                $set('razao_social', $data['razao_social'] ?? null);
                                                $set('nome_fantasia', $data['nome_fantasia'] ?? null);
                                                
                                                $endereco = trim(($data['logradouro'] ?? '') . ' ' . ($data['numero'] ?? '') . ' ' . ($data['complemento'] ?? ''));
                                                $set('endereco', $endereco);
                                                $set('bairro', $data['bairro'] ?? null);
                                                $set('cidade', $data['municipio'] ?? null);
                                                $set('estado', $data['uf'] ?? null);
                                                
                                                if (isset($data['cep'])) {
                                                    $cep = preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $data['cep']);
                                                    $set('cep', $cep);
                                                }
                                            } else {
                                                \Filament\Notifications\Notification::make()->title('CNPJ não encontrado na BrasilAPI')->danger()->send();
                                            }
                                        } catch (\Exception $e) {
                                            \Filament\Notifications\Notification::make()->title('Erro ao buscar CNPJ')->danger()->send();
                                        }
                                    }),

                                Forms\Components\TextInput::make('razao_social')
                                    ->label(fn ($get) => $get('tipo_pessoa') === 'PJ' ? 'Razão Social' : 'Nome Completo')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('nome_fantasia')
                                    ->label('Nome Fantasia / Apelido')
                                    ->maxLength(255),
                                
                                Forms\Components\Select::make('classificacao')
                                    ->label('Classificação')
                                    ->options([
                                        'Fornecedor' => 'Fornecedor',
                                        'Cliente' => 'Cliente',
                                        'Concorrente' => 'Concorrente',
                                    ])
                                    ->default('Fornecedor')
                                    ->required(),

                                Forms\Components\Toggle::make('status')
                                    ->label('Ativo')
                                    ->default(true),
                            ])->columns(2),

                        \Filament\Schemas\Components\Tabs\Tab::make('Contato da Empresa')
                            ->schema([
                                \Filament\Schemas\Components\Section::make('Telefones e Email Gerais')
                                    ->schema([
                                        Forms\Components\TextInput::make('email')
                                            ->label('E-mail Geral')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('telefone')
                                            ->label('Telefone Fixo')
                                            ->mask('(99) 9999-9999')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('celular')
                                            ->label('Celular/WhatsApp')
                                            ->mask('(99) 99999-9999')
                                            ->maxLength(255),
                                    ])->columns(3),

                                \Filament\Schemas\Components\Section::make('Pessoa de Contato')
                                    ->description('Informações do responsável ou ponto de contato direto')
                                    ->schema([
                                        Forms\Components\TextInput::make('contato_nome')
                                            ->label('Nome do Contato')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contato_cargo')
                                            ->label('Cargo')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contato_setor')
                                            ->label('Setor')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contato_email')
                                            ->label('E-mail do Contato')
                                            ->email()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('contato_telefone')
                                            ->label('Telefone/Celular do Contato')
                                            ->maxLength(255),
                                    ])->columns(2),
                            ]),

                        \Filament\Schemas\Components\Tabs\Tab::make('Endereço')
                            ->schema([
                                Forms\Components\TextInput::make('cep')
                                    ->label('CEP')
                                    ->mask('99999-999')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('endereco')
                                    ->label('Endereço (Rua, Av)')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('numero')
                                    ->label('Número')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('complemento')
                                    ->label('Complemento')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('bairro')
                                    ->label('Bairro')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('cidade')
                                    ->label('Cidade')
                                    ->maxLength(255),
                                Forms\Components\Select::make('estado')
                                    ->label('Estado')
                                    ->options([
                                        'AC' => 'AC', 'AL' => 'AL', 'AP' => 'AP', 'AM' => 'AM',
                                        'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES',
                                        'GO' => 'GO', 'MA' => 'MA', 'MT' => 'MT', 'MS' => 'MS',
                                        'MG' => 'MG', 'PA' => 'PA', 'PB' => 'PB', 'PR' => 'PR',
                                        'PE' => 'PE', 'PI' => 'PI', 'RJ' => 'RJ', 'RN' => 'RN',
                                        'RS' => 'RS', 'RO' => 'RO', 'RR' => 'RR', 'SC' => 'SC',
                                        'SP' => 'SP', 'SE' => 'SE', 'TO' => 'TO'
                                    ])
                                    ->searchable(),
                            ])->columns(3),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('razao_social')
                    ->label('NOME / RAZÃO SOCIAL')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf_cnpj')
                    ->label('CNPJ/CPF')
                    ->searchable(),
                Tables\Columns\TextColumn::make('classificacao')
                    ->label('CLASSIFICAÇÃO')
                    ->badge()
                    ->colors([
                        'primary' => 'Fornecedor',
                        'success' => 'Cliente',
                        'danger' => 'Concorrente',
                    ]),
                Tables\Columns\TextColumn::make('cidade')
                    ->label('CIDADE')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('UF'),
                Tables\Columns\IconColumn::make('status')
                    ->label('ATIVO')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('classificacao')
                    ->label('Classificação')
                    ->options([
                        'Fornecedor' => 'Fornecedor',
                        'Cliente' => 'Cliente',
                        'Concorrente' => 'Concorrente',
                    ]),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Status (Ativo/Inativo)'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()->button()->color('info'),
                \Filament\Actions\DeleteAction::make()->button()->color('danger'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFornecedores::route('/'),
            'create' => Pages\CreateFornecedor::route('/create'),
            'edit' => Pages\EditFornecedor::route('/{record}/edit'),
        ];
    }
}
