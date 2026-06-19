<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItensRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';

    protected static ?string $title = 'Itens e Propostas';

    protected static ?string $recordTitleAttribute = 'numero_item';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('numero_item')
                    ->label('Item')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('descricao')
                    ->label('Descrição')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('fabricante')
                    ->label('Fabricante')
                    ->maxLength(255),
                Forms\Components\TextInput::make('modelo')
                    ->label('Modelo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('marca')
                    ->label('Marca')
                    ->maxLength(255),
                Forms\Components\TextInput::make('valor_unitario')
                    ->label('Valor Unitário')
                    ->numeric()
                    ->prefix('R$')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $qtd = $get('quantidade') ?? 1;
                        $set('valor_total', floatval($state) * floatval($qtd));
                    }),
                Forms\Components\TextInput::make('quantidade')
                    ->label('Qtd')
                    ->numeric()
                    ->default(1)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $vu = $get('valor_unitario') ?? 0;
                        $set('valor_total', floatval($state) * floatval($vu));
                    }),
                Forms\Components\TextInput::make('valor_total')
                    ->label('Valor Total')
                    ->numeric()
                    ->prefix('R$')
                    ->readOnly(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Ganho' => 'Ganho',
                        'Perdido' => 'Perdido',
                        'Desclassificado' => 'Desclassificado',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero_item')
            ->columns([
                Tables\Columns\TextColumn::make('numero_item')->label('ITEM'),
                Tables\Columns\TextColumn::make('descricao')->label('DESCRIÇÃO')->limit(30),
                Tables\Columns\TextColumn::make('fabricante')->label('FABRICANTE'),
                Tables\Columns\TextColumn::make('modelo')->label('MODELO'),
                Tables\Columns\TextColumn::make('valor_unitario')->label('V. UNIT')->money('BRL'),
                Tables\Columns\TextColumn::make('valor_total')->label('V. TOTAL')->money('BRL'),
                Tables\Columns\TextColumn::make('marca')->label('MARCA'),
                Tables\Columns\TextColumn::make('status')->label('STATUS')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('Adicionar Nova Proposta'),
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
