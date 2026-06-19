<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\Oportunidade;
use Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

class OportunidadeResource extends Resource
{
    protected static ?string $model = Oportunidade::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Oportunidade (Funil)';

    protected static ?string $pluralModelLabel = 'Funil de Vendas (Lista)';

    protected static ?string $slug = 'oportunidades';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Detalhes da Oportunidade')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título / Nome da Oportunidade')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('fornecedor_id')
                            ->label('Cliente')
                            ->relationship('fornecedor', 'razao_social')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('status')
                            ->label('Fase do Funil')
                            ->options([
                                'Prospecção' => 'Prospecção',
                                'Qualificação' => 'Qualificação',
                                'Proposta' => 'Proposta',
                                'Negociação' => 'Negociação',
                                'Fechado/Ganho' => 'Fechado/Ganho',
                                'Fechado/Perdido' => 'Fechado/Perdido',
                            ])
                            ->default('Prospecção')
                            ->required(),
                        Forms\Components\TextInput::make('valor_estimado')
                            ->label('Valor Estimado (R$)')
                            ->numeric()
                            ->default(0),
                        Forms\Components\DatePicker::make('data_fechamento_esperada')
                            ->label('Fechamento Esperado'),
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Fase')
                    ->badge()
                    ->colors([
                        'secondary' => 'Prospecção',
                        'warning' => 'Qualificação',
                        'primary' => 'Proposta',
                        'info' => 'Negociação',
                        'success' => 'Fechado/Ganho',
                        'danger' => 'Fechado/Perdido',
                    ]),
                Tables\Columns\TextColumn::make('valor_estimado')
                    ->label('Valor Estimado')
                    ->money('BRL')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_fechamento_esperada')
                    ->label('Fechamento')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Prospecção' => 'Prospecção',
                        'Qualificação' => 'Qualificação',
                        'Proposta' => 'Proposta',
                        'Negociação' => 'Negociação',
                        'Fechado/Ganho' => 'Fechado/Ganho',
                        'Fechado/Perdido' => 'Fechado/Perdido',
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('status')
                    ->label('Fase do Funil')
                    ->collapsible(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()->label('Editar')->button()->color('info'),
                \Filament\Actions\DeleteAction::make()->label('Excluir')->button()->color('danger'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOportunidades::route('/'),
            'create' => Pages\CreateOportunidade::route('/create'),
            'edit' => Pages\EditOportunidade::route('/{record}/edit'),
        ];
    }
}
