<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\TarefaAgenda;
use Modules\Comercial\Filament\Resources\TarefaAgendaResource\Pages;

class TarefaAgendaResource extends Resource
{
    protected static ?string $model = TarefaAgenda::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar-days';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Tarefa / Reunião';

    protected static ?string $pluralModelLabel = 'Agenda (Lista)';

    protected static ?string $slug = 'agenda-tarefas';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Detalhes da Tarefa')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('data_inicio')
                            ->label('Início')
                            ->required(),
                        Forms\Components\DateTimePicker::make('data_fim')
                            ->label('Fim'),
                        Forms\Components\Toggle::make('dia_inteiro')
                            ->label('Dia Inteiro')
                            ->default(false),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pendente' => 'Pendente',
                                'Em andamento' => 'Em andamento',
                                'Concluída' => 'Concluída',
                                'Cancelada' => 'Cancelada',
                            ])
                            ->default('Pendente')
                            ->required(),
                        Forms\Components\Select::make('fornecedor_id')
                            ->label('Cliente Relacionado')
                            ->relationship('fornecedor', 'razao_social')
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('oportunidade_id')
                            ->label('Oportunidade / Negócio')
                            ->relationship('oportunidade', 'titulo')
                            ->searchable()
                            ->preload(),

                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\ViewField::make('historico')
                            ->view('comercial::filament.forms.tarefa-historico')
                            ->dehydrated(false)
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
                Tables\Columns\TextColumn::make('data_inicio')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'Pendente',
                        'primary' => 'Em andamento',
                        'success' => 'Concluída',
                        'danger' => 'Cancelada',
                    ]),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('oportunidade.titulo')
                    ->label('Oportunidade')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pendente' => 'Pendente',
                        'Em andamento' => 'Em andamento',
                        'Concluída' => 'Concluída',
                        'Cancelada' => 'Cancelada',
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('data_inicio')
                    ->label('Data')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('status')
                    ->label('Status')
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
            'index' => Pages\ListTarefaAgendas::route('/'),
            'create' => Pages\CreateTarefaAgenda::route('/create'),
            'edit' => Pages\EditTarefaAgenda::route('/{record}/edit'),
        ];
    }
}
