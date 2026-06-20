<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\Oportunidade;
use Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

class OportunidadeResource extends Resource
{
    protected static ?string $model = Oportunidade::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Oportunidade (Funil)';

    protected static ?string $pluralModelLabel = 'Funil de Vendas (Lista)';

    protected static ?string $slug = 'oportunidades';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações Principais')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pessoa_contato_nome')->label('Pessoa de contato'),
                                Forms\Components\Select::make('fornecedor_id')
                                    ->label('Organização')
                                    ->relationship('fornecedor', 'razao_social')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('pessoa_contato_telefone')->label('Telefone'),
                                Forms\Components\TextInput::make('pessoa_contato_email')->label('E-mail')->email(),
                            ]),
                        
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        \Filament\Schemas\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\Repeater::make('oportunidadeProdutos')
                                    ->relationship('oportunidadeProdutos')
                                    ->label('Adicionar Produtos')
                                    ->addActionLabel('Adicionar Produto')
                                    ->live()
                                    ->schema([
                                        Forms\Components\Select::make('produto_id')
                                            ->label('Produto')
                                            ->relationship('produto', 'nome')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state) {
                                                    $produto = \Modules\Comercial\Models\Produto::find($state);
                                                    if ($produto) {
                                                        $set('preco_unitario', $produto->valor_unitario);
                                                    }
                                                }
                                            })
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('quantidade')
                                            ->label('Qtd')
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->live()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('preco_unitario')
                                            ->label('Preço Unit.')
                                            ->numeric()
                                            ->required()
                                            ->live()
                                            ->columnSpan(1),
                                    ])
                                    ->columns(4),

                                Forms\Components\Placeholder::make('total_calculado')
                                    ->label('Valor Total')
                                    ->content(function ($get) {
                                        $produtos = $get('oportunidadeProdutos') ?? [];
                                        $total = 0;
                                        foreach ($produtos as $produto) {
                                            $qtd = (float) ($produto['quantidade'] ?? 0);
                                            $preco = (float) ($produto['preco_unitario'] ?? 0);
                                            $total += $qtd * $preco;
                                        }
                                        return 'R$ ' . number_format($total, 2, ',', '.');
                                    }),
                            ]),
                    ]),

                \Filament\Schemas\Components\Section::make('Pipeline e Detalhes')
                    ->schema([
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('funil_selecionado')
                                    ->label('Funil')
                                    ->options([
                                        'Funil de vendas' => 'Funil de vendas',
                                        'Funil de onboarding' => 'Funil de onboarding',
                                    ])
                                    ->default('Funil de vendas')
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Pipeline stage')
                                    ->options([
                                        'Prospectando' => 'Prospectando',
                                        'Proposta' => 'Proposta',
                                        'Negociação' => 'Negociação',
                                        'Fechado / Aprovado' => 'Fechado / Aprovado',
                                        'Perdido / Recusado' => 'Perdido / Recusado',
                                    ])
                                    ->default('Prospectando')
                                    ->required()
                                    ->live(),
                            ]),
                        \Filament\Schemas\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('data_fechamento_esperada')
                                    ->label('Data de fechamento esperada'),
                                Forms\Components\Select::make('user_id')
                                    ->label('Proprietário')
                                    ->relationship('user', 'name')
                                    ->default(auth()->id()),
                            ]),
                        Forms\Components\Select::make('visibilidade')
                            ->label('Visível para')
                            ->options([
                                'Proprietário do item' => 'Proprietário do item',
                                'Grupo de visibilidade' => 'Grupo de visibilidade',
                                'Todos os usuários' => 'Todos os usuários',
                            ])
                            ->default('Todos os usuários')
                            ->columnSpanFull(),
                    ]),

                \Filament\Schemas\Components\Section::make('Agendamento de Tarefa')
                    ->schema([
                        Forms\Components\Toggle::make('criar_tarefa')
                            ->label('Agendar uma Tarefa para esta Oportunidade?')
                            ->live()
                            ->default(false),
                        Forms\Components\DateTimePicker::make('data_tarefa')
                            ->label('Data e Hora')
                            ->visible(fn ($get) => $get('criar_tarefa'))
                            ->required(fn ($get) => $get('criar_tarefa'))
                            ->default(now()->addDay()->setHour(9)->setMinute(0)),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Fase')
                    ->badge()
                    ->colors([
                        'secondary' => 'Prospectando',
                        'primary' => 'Proposta',
                        'warning' => 'Negociação',
                        'success' => 'Fechado / Aprovado',
                        'danger' => 'Perdido / Recusado',
                    ]),
                Tables\Columns\TextColumn::make('valor_estimado')
                    ->label('Valor Estimado')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_fechamento_esperada')
                    ->label('Fechamento')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Prospectando' => 'Prospectando',
                        'Proposta' => 'Proposta',
                        'Negociação' => 'Negociação',
                        'Fechado / Aprovado' => 'Fechado / Aprovado',
                        'Perdido / Recusado' => 'Perdido / Recusado',
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('status')
                    ->label('Fase do Funil')
                    ->collapsible(),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make()->label('Abrir')->button()->color('gray'),
                \Filament\Tables\Actions\EditAction::make()->label('Editar')->button()->color('info'),
                \Filament\Tables\Actions\DeleteAction::make()->label('Excluir')->button()->color('danger'),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OportunidadeResource\RelationManagers\PropostasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOportunidades::route('/'),
            'create' => Pages\CreateOportunidade::route('/create'),
            'edit' => Pages\EditOportunidade::route('/{record}/edit'),
            'view' => Pages\ViewOportunidade::route('/{record}'),
        ];
    }
}
