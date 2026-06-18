<?php

namespace Modules\Licitacoes\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Licitacoes\Models\Licitacao;
use Modules\Licitacoes\Filament\Resources\LicitacaoResource\Pages;

class LicitacaoResource extends Resource
{
    protected static ?string $model = Licitacao::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static \UnitEnum|string|null $navigationGroup = 'Vendas Públicas';

    protected static ?string $modelLabel = 'Pregão';

    protected static ?string $pluralModelLabel = 'Pregões';

    protected static ?string $slug = 'pregoes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('numero_edital')
                            ->label('Número do Edital')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('numero_processo')
                            ->label('Número do Processo')
                            ->maxLength(255),
                        Forms\Components\Select::make('modalidade')
                            ->label('Modalidade')
                            ->options([
                                'Pregão Eletrônico' => 'Pregão Eletrônico',
                                'Compra Direta' => 'Compra Direta',
                                'Dispensa de Licitação' => 'Dispensa de Licitação',
                                'Pregão Presencial' => 'Pregão Presencial',
                                'Concorrência' => 'Concorrência',
                                'Tomada de Preços' => 'Tomada de Preços',
                                'Convite' => 'Convite',
                                'Outra' => 'Outra',
                            ])
                            ->default('Pregão Eletrônico'),
                        Forms\Components\TextInput::make('local_disputa')
                            ->label('Local da Disputa')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('uasg')
                            ->label('UASG')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('ÓRGÃO COMPRADOR')
                    ->schema([
                        Forms\Components\TextInput::make('orgao_cnpj')
                            ->label('CNPJ')
                            ->mask('99.999.999/9999-99')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('orgao_razao_social')
                            ->label('Razão Social')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('orgao_nome_fantasia')
                            ->label('Nome Fantasia')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('orgao_endereco')
                            ->label('Endereço')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('orgao_bairro')
                            ->label('Bairro')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('orgao_cidade')
                            ->label('Cidade')
                            ->maxLength(255),
                        Forms\Components\Select::make('orgao_estado')
                            ->label('Estado')
                            ->options([
                                'AC' => 'AC', 'AL' => 'AL', 'AP' => 'AP', 'AM' => 'AM',
                                'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES',
                                'GO' => 'GO', 'MA' => 'MA', 'MT' => 'MT', 'MS' => 'MS',
                                'MG' => 'MG', 'PA' => 'PA', 'PB' => 'PB', 'PR' => 'PR',
                                'PE' => 'PE', 'PI' => 'PI', 'RJ' => 'RJ', 'RN' => 'RN',
                                'RS' => 'RS', 'RO' => 'RO', 'RR' => 'RR', 'SC' => 'SC',
                                'SP' => 'SP', 'SE' => 'SE', 'TO' => 'TO'
                            ])
                            ->searchable(),
                        Forms\Components\TextInput::make('orgao_cep')
                            ->label('CEP')
                            ->mask('99999-999')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\Textarea::make('objeto')
                            ->label('Objeto')
                            ->rows(4)
                            ->required(),
                    ]),

                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\DatePicker::make('data_disputa')
                            ->label('Data de Abertura/Disputa'),
                        Forms\Components\TimePicker::make('hora_disputa')
                            ->label('Hora da Disputa'),
                    ]),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Em análise' => 'Em análise',
                        'Acolhimento de propostas' => 'Acolhimento de propostas',
                        'Homologado' => 'Homologado',
                        'Revogado' => 'Revogado',
                        'Fracassado' => 'Fracassado',
                        'Anulado' => 'Anulado',
                        'Adjudicado' => 'Adjudicado',
                        'Suspenso' => 'Suspenso',
                    ])
                    ->default('Em análise')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_edital')
                    ->label('EDITAL')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orgao_razao_social')
                    ->label('ÓRGÃO')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_disputa')
                    ->label('DATA DA DISPUTA')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('STATUS')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Em análise' => 'Em análise',
                        'Acolhimento de propostas' => 'Acolhimento de propostas',
                        'Homologado' => 'Homologado',
                        'Revogado' => 'Revogado',
                        'Fracassado' => 'Fracassado',
                        'Anulado' => 'Anulado',
                        'Adjudicado' => 'Adjudicado',
                        'Suspenso' => 'Suspenso',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detalhes')->button()->color('primary'),
                Tables\Actions\EditAction::make()->label('Editar')->button()->color('info'),
                Tables\Actions\DeleteAction::make()->label('Excluir')->button()->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLicitacoes::route('/'),
            'create' => Pages\CreateLicitacao::route('/create'),
            'view' => Pages\ViewLicitacao::route('/{record}'),
            'edit' => Pages\EditLicitacao::route('/{record}/edit'),
        ];
    }
}

