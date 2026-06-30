<?php

namespace Modules\Consignado\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Consignado\Models\Saldo;
use Modules\Consignado\Filament\Resources\SaldoResource\Pages;
use Illuminate\Database\Eloquent\Builder;

class SaldoResource extends Resource
{
    protected static ?string $model = Saldo::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';
    protected static \UnitEnum|string|null $navigationGroup = 'Consignado';
    protected static ?string $modelLabel = 'Saldo no Cliente';
    protected static ?string $pluralModelLabel = 'Estoque nos Clientes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Read-only, or no form at all.
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente/Fornecedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('produto.nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lote')
                    ->label('Lote')
                    ->searchable(),
                Tables\Columns\TextColumn::make('validade')
                    ->label('Validade')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantidade')
                    ->label('Saldo na Prateleira')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_utilizado')
                    ->label('Total Utilizado')
                    ->getStateUsing(function ($record) {
                        return \Modules\Consignado\Models\MovimentacaoItem::where('produto_id', $record->produto_id)
                            ->whereHas('movimentacao', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id)->where('tipo', 'Consumo'))
                            ->sum('quantidade');
                    }),
                Tables\Columns\TextColumn::make('falta_cota')
                    ->label('Falta na Cota')
                    ->getStateUsing(function ($record) {
                        $cota = \Modules\Consignado\Models\ContratoItem::where('produto_id', $record->produto_id)
                            ->whereHas('contrato', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id))
                            ->value('cota_base') ?? 0;
                        $utilizado = \Modules\Consignado\Models\MovimentacaoItem::where('produto_id', $record->produto_id)
                            ->whereHas('movimentacao', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id)->where('tipo', 'Consumo'))
                            ->sum('quantidade');
                        return ($cota - $utilizado) . ' / ' . $cota;
                    }),
                Tables\Columns\TextColumn::make('valor_faturado')
                    ->label('Faturado')
                    ->money('BRL')
                    ->getStateUsing(function ($record) {
                        $preco = \Modules\Consignado\Models\ContratoItem::where('produto_id', $record->produto_id)
                            ->whereHas('contrato', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id))
                            ->value('valor_unitario') ?? 0;
                        $qtd_faturada = \Modules\Consignado\Models\MovimentacaoItem::where('produto_id', $record->produto_id)
                            ->where('faturado', true)
                            ->whereHas('movimentacao', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id)->where('tipo', 'Consumo'))
                            ->sum('quantidade');
                        return $qtd_faturada * $preco;
                    }),
                Tables\Columns\TextColumn::make('valor_a_faturar')
                    ->label('A Faturar')
                    ->money('BRL')
                    ->color('danger')
                    ->getStateUsing(function ($record) {
                        $preco = \Modules\Consignado\Models\ContratoItem::where('produto_id', $record->produto_id)
                            ->whereHas('contrato', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id))
                            ->value('valor_unitario') ?? 0;
                        $qtd_a_faturar = \Modules\Consignado\Models\MovimentacaoItem::where('produto_id', $record->produto_id)
                            ->where('faturado', false)
                            ->whereHas('movimentacao', fn($q) => $q->where('fornecedor_id', $record->fornecedor_id)->where('tipo', 'Consumo'))
                            ->sum('quantidade');
                        return $qtd_a_faturar * $preco;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('fornecedor_id')
                    ->label('Filtrar por Cliente')
                    ->relationship('fornecedor', 'razao_social'),
            ])
            ->actions([
                // No edit/delete actions, balance is managed via Movimentações
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('quantidade', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSaldos::route('/'),
        ];
    }
}
