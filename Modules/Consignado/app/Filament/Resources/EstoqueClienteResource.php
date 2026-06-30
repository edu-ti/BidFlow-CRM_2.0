<?php

namespace Modules\Consignado\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Fornecedores\Models\Fornecedor;
use Modules\Consignado\Filament\Resources\EstoqueClienteResource\Pages;
use Modules\Consignado\Filament\Resources\EstoqueClienteResource\RelationManagers;
use Illuminate\Database\Eloquent\Builder;

class EstoqueClienteResource extends Resource
{
    protected static ?string $model = Fornecedor::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';
    protected static \UnitEnum|string|null $navigationGroup = 'Consignado';
    protected static ?string $modelLabel = 'Estoque no Cliente';
    protected static ?string $pluralModelLabel = 'Estoque nos Clientes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('razao_social')
                    ->label('Razão Social')
                    ->disabled(),
                Forms\Components\TextInput::make('cpf_cnpj')
                    ->label('CNPJ/CPF')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Exibir apenas clientes que possuem saldos de consignado
            ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('saldos'))
            ->columns([
                Tables\Columns\TextColumn::make('razao_social')
                    ->label('Cliente/Fornecedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cpf_cnpj')
                    ->label('CNPJ/CPF')
                    ->searchable(),
                Tables\Columns\TextColumn::make('saldos_count')
                    ->label('Itens Diferentes em Estoque')
                    ->counts('saldos')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\ViewAction::make()->label('Ver Estoque'),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('razao_social', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SaldosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstoqueClientes::route('/'),
            'view' => Pages\ViewEstoqueCliente::route('/{record}'),
        ];
    }
}
