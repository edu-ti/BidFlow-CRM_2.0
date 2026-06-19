<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\PropostaComercial;
use Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

class PropostaComercialResource extends Resource
{
    protected static ?string $model = PropostaComercial::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Proposta Comercial';

    protected static ?string $pluralModelLabel = 'Propostas Comerciais';

    protected static ?string $slug = 'propostas-comerciais';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações da Proposta')
                    ->schema([
                        Forms\Components\TextInput::make('numero')
                            ->label('Número da Proposta')
                            ->default(fn () => 'PROP-' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('fornecedor_id')
                            ->label('Cliente')
                            ->relationship('fornecedor', 'razao_social')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('data_proposta')
                            ->label('Data da Proposta')
                            ->default(now()),
                        Forms\Components\DatePicker::make('validade')
                            ->label('Validade'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Em elaboração' => 'Em elaboração',
                                'Enviada' => 'Enviada',
                                'Em Negociação' => 'Em Negociação',
                                'Aprovada' => 'Aprovada',
                                'Recusada' => 'Recusada',
                            ])
                            ->default('Em elaboração')
                            ->required(),
                        Forms\Components\Textarea::make('observacoes')
                            ->label('Observações')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Itens da Proposta')
                    ->schema([
                        Forms\Components\Repeater::make('itens')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('produto_id')
                                    ->label('Produto')
                                    ->relationship('produto', 'nome')
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $produto = \Modules\Comercial\Models\Produto::find($state);
                                            if ($produto) {
                                                $set('descricao', $produto->nome);
                                                $set('valor_unitario', $produto->valor_unitario);
                                                $set('valor_total', $produto->valor_unitario);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('descricao')
                                    ->label('Descrição Customizada')
                                    ->required(),
                                Forms\Components\TextInput::make('quantidade')
                                    ->label('Quantidade')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => $set('valor_total', (float)$state * (float)$get('valor_unitario'))),
                                Forms\Components\TextInput::make('valor_unitario')
                                    ->label('Valor Unitário')
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set, callable $get) => $set('valor_total', (float)$state * (float)$get('quantidade'))),
                                Forms\Components\TextInput::make('valor_total')
                                    ->label('Valor Total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_proposta')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'Em elaboração',
                        'primary' => 'Enviada',
                        'info' => 'Em Negociação',
                        'success' => 'Aprovada',
                        'danger' => 'Recusada',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make()->label('Editar')->button()->color('info'),
                \Filament\Actions\DeleteAction::make()->label('Excluir')->button()->color('danger'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropostaComercials::route('/'),
            'create' => Pages\CreatePropostaComercial::route('/create'),
            'edit' => Pages\EditPropostaComercial::route('/{record}/edit'),
        ];
    }
}
