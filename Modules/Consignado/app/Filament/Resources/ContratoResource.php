<?php

namespace Modules\Consignado\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Consignado\Models\Contrato;
use Modules\Consignado\Filament\Resources\ContratoResource\Pages;
use Modules\Consignado\Filament\Resources\ContratoResource\RelationManagers;

class ContratoResource extends Resource
{
    protected static ?string $model = Contrato::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    protected static \UnitEnum|string|null $navigationGroup = 'Consignado';
    protected static ?string $modelLabel = 'Contrato de Consignação';
    protected static ?string $pluralModelLabel = 'Contratos de Consignação';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('fornecedor_id')
                    ->label('Cliente/Fornecedor')
                    ->relationship('fornecedor', 'razao_social')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('numero_contrato')
                    ->label('Número do Contrato')
                    ->maxLength(255),
                Forms\Components\TextInput::make('processo_pregao')
                    ->label('Processo / Pregão')
                    ->maxLength(255),

                Forms\Components\DatePicker::make('data_inicio')
                    ->label('Data de Início'),
                Forms\Components\DatePicker::make('data_fim')
                    ->label('Data de Fim'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Ativo' => 'Ativo',
                        'Encerrado' => 'Encerrado',
                    ])
                    ->default('Ativo')
                    ->required(),
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
                Tables\Columns\TextColumn::make('numero_contrato')->searchable(),
                Tables\Columns\TextColumn::make('processo_pregao')
                    ->label('Processo/Pregão')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ativo' => 'success',
                        'Encerrado' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('data_fim')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
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
            RelationManagers\ItensRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContratos::route('/'),
            'create' => Pages\CreateContrato::route('/create'),
            'edit' => Pages\EditContrato::route('/{record}/edit'),
        ];
    }
}
