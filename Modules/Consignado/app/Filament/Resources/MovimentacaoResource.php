<?php

namespace Modules\Consignado\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Consignado\Models\Movimentacao;
use Modules\Consignado\Filament\Resources\MovimentacaoResource\Pages;

class MovimentacaoResource extends Resource
{
    protected static ?string $model = Movimentacao::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static \UnitEnum|string|null $navigationGroup = 'Consignado';
    protected static ?string $modelLabel = 'Lançar Movimentação';
    protected static ?string $pluralModelLabel = 'Movimentações (Extrato)';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('fornecedor_id')
                    ->label('Cliente/Fornecedor')
                    ->relationship('fornecedor', 'razao_social')
                    ->required()
                    ->searchable()
                    ->columnSpanFull(),
                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Movimento')
                    ->options([
                        'Remessa' => 'Remessa (Enviou)',
                        'Consumo' => 'Consumo (Usou)',
                        'Devolução' => 'Devolução (Retornou)',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('data_movimento')
                    ->label('Data do Ocorrido')
                    ->default(now())
                    ->required(),
                Forms\Components\Repeater::make('itens')
                    ->relationship('itens')
                    ->label('Produtos da Movimentação')
                    ->schema([
                        Forms\Components\Select::make('produto_id')
                            ->label('Produto')
                            ->relationship('produto', 'nome')
                            ->required()
                            ->searchable()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->numeric()
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('lote')
                            ->label('Lote')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\DatePicker::make('validade')
                            ->label('Data de Validade')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('faturado')
                            ->label('Já Faturado?')
                            ->default(false)
                            ->columnSpan(1),
                    ])
                    ->columns(6)
                    ->columnSpanFull()
                    ->minItems(1)
                    ->required(),
                Forms\Components\Textarea::make('observacao')
                    ->label('Observações (Paciente, O.S., etc)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('data_movimento')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente/Fornecedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Remessa' => 'info',
                        'Consumo' => 'danger',
                        'Devolução' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('itens_count')
                    ->counts('itens')
                    ->label('Qtd de Produtos'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fornecedor_id')
                    ->label('Cliente/Fornecedor')
                    ->relationship('fornecedor', 'razao_social'),
                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'Remessa' => 'Remessa',
                        'Consumo' => 'Consumo',
                        'Devolução' => 'Devolução',
                    ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMovimentacaos::route('/'),
        ];
    }
}
