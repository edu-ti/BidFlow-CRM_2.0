<?php

namespace Modules\Licitacoes\Filament\Resources\LicitacaoResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnexosRelationManager extends RelationManager
{
    protected static string $relationship = 'anexos';

    protected static ?string $title = 'Anexos do Pregão';

    protected static ?string $recordTitleAttribute = 'nome';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Hidden::make('tipo')
                    ->default('anexo_pregao'),
                Forms\Components\TextInput::make('nome')
                    ->label('Nome do Anexo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('arquivo_path')
                    ->label('Arquivo')
                    ->directory('licitacoes/anexos')
                    ->preserveFilenames()
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('tipo', 'anexo_pregao'))
            ->recordTitleAttribute('nome')
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome'),
                Tables\Columns\TextColumn::make('arquivo_path')
                    ->label('Arquivo')
                    ->formatStateUsing(fn ($state) => basename($state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enviado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()->label('Adicionar Anexo'),
            ])
            ->actions([
                \Filament\Actions\Action::make('download')
                    ->label('Baixar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => asset('storage/' . $record->arquivo_path))
                    ->openUrlInNewTab(),
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
