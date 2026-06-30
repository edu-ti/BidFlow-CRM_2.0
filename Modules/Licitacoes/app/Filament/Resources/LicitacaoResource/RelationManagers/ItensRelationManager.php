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
                \Filament\Schemas\Components\Section::make('DADOS DO ITEM (REFERÊNCIA)')
                    ->schema([
                        Forms\Components\TextInput::make('numero_lote')
                            ->label('Nº do Lote (Opcional)')
                            ->placeholder('Ex: Lote 01')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('numero_item')
                            ->label('Nº do Item')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('quantidade')
                            ->label('Quantidade')
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('valor_unit_referencia')
                            ->label('Valor Unit. Referência (R$)')
                            ->numeric()
                            ->prefix('R$'),
                        Forms\Components\Select::make('status')
                            ->label('Status do Item')
                            ->options([
                                'Em Análise' => 'Em Análise',
                                'Acolhimento de Proposta' => 'Acolhimento de Proposta',
                                'Homologado' => 'Homologado',
                                'Revogado' => 'Revogado',
                                'Fracassado' => 'Fracassado',
                                'Anulado' => 'Anulado',
                                'Suspenso' => 'Suspenso',
                                'Adjudicado' => 'Adjudicado',
                                'Deserto' => 'Deserto',
                            ])
                            ->default('Acolhimento de Proposta'),
                        Forms\Components\Select::make('tipo_cota')
                            ->label('Tipo de Cota')
                            ->options([
                                'Ampla Concorrência' => 'Ampla Concorrência',
                                'Cota Exclusiva' => 'Cota Exclusiva',
                                'Cota Reservada' => 'Cota Reservada',
                            ])
                            ->default('Ampla Concorrência'),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('PARTICIPANTES')
                    ->schema([
                        Forms\Components\Repeater::make('participantes')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('fornecedor_id')
                                    ->label('Fornecedor')
                                    ->relationship('fornecedor', 'razao_social')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('fabricante_marca')
                                    ->label('Fabricante/Marca')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('modelo')
                                    ->label('Modelo')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('valor_unitario')
                                    ->label('Valor Unitário (R$)')
                                    ->numeric()
                                    ->prefix('R$'),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Classificada' => 'Classificada',
                                        'Desclassificada' => 'Desclassificada',
                                        'Inabilitada' => 'Inabilitada',
                                        'Em Negociação' => 'Em Negociação',
                                        'Aceita' => 'Aceita',
                                        'Adjudicado' => 'Adjudicado',
                                        'Homologado' => 'Homologado',
                                    ])
                                    ->default('Classificada'),
                            ])
                            ->columns(2)
                            ->addActionLabel('Adicionar Participante')
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero_item')
            ->columns([
                Tables\Columns\TextColumn::make('numero_lote')->label('LOTE'),
                Tables\Columns\TextColumn::make('numero_item')->label('ITEM'),
                Tables\Columns\TextColumn::make('descricao')->label('DESCRIÇÃO')->limit(40),
                Tables\Columns\TextColumn::make('quantidade')->label('QTD'),
                Tables\Columns\TextColumn::make('valor_unit_referencia')->label('VALOR REF.')->money('BRL'),
                Tables\Columns\TextColumn::make('status')->label('STATUS')->badge(),
                Tables\Columns\TextColumn::make('tipo_cota')->label('COTA'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('Adicionar Novo Item')
                    ->modalHeading('Adicionar Item')
                    ->modalWidth('5xl'),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->modalHeading('Editar Item Completo')
                    ->modalWidth('5xl'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
