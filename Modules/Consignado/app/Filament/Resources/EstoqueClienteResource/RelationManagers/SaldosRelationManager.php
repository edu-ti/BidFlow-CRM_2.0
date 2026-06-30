<?php

namespace Modules\Consignado\Filament\Resources\EstoqueClienteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaldosRelationManager extends RelationManager
{
    protected static string $relationship = 'saldos';
    protected static ?string $title = 'Itens em Estoque';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('produto_id')
            ->columns([
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
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('quantidade', 'desc');
    }
}
