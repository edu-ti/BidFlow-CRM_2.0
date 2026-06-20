<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PropostasRelationManager extends RelationManager
{
    protected static string $relationship = 'propostas';

    protected static ?string $recordTitleAttribute = 'numero';

    protected static ?string $title = 'Propostas Comerciais';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->label('Número')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'Em elaboração' => 'Em elaboração',
                        'Enviada' => 'Enviada',
                        'Em Negociação' => 'Em Negociação',
                        'Aprovada' => 'Aprovada',
                        'Recusada' => 'Recusada',
                    ])
                    ->default('Em elaboração')
                    ->required(),
                Forms\Components\TextInput::make('valor_total')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero')
            ->columns([
                Tables\Columns\TextColumn::make('numero')->label('Número'),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'Em elaboração',
                    'primary' => 'Enviada',
                    'info' => 'Em Negociação',
                    'success' => 'Aprovada',
                    'danger' => 'Recusada',
                ]),
                Tables\Columns\TextColumn::make('valor_total')->money('BRL'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['fornecedor_id'] = $this->getOwnerRecord()->fornecedor_id;
                        return $data;
                    }),
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
