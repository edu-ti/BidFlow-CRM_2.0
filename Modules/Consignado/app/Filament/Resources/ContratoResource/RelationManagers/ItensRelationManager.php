<?php

namespace Modules\Consignado\Filament\Resources\ContratoResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItensRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';
    protected static ?string $recordTitleAttribute = 'produto_id';
    protected static ?string $modelLabel = 'Item Consignado';
    protected static ?string $pluralModelLabel = 'Itens Consignados (Cotas Base)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('produto_id')
                    ->label('Produto')
                    ->relationship('produto', 'nome')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('cota_base')
                    ->label('Cota Base (Qtd Ideal no Cliente)')
                    ->numeric()
                    ->required()
                    ->default(0),
                Forms\Components\TextInput::make('valor_unitario')
                    ->label('Valor Unitário (R$)')
                    ->numeric()
                    ->prefix('R$')
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produto.nome')
                    ->label('Produto'),
                Tables\Columns\TextColumn::make('cota_base')
                    ->label('Cota Base'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
