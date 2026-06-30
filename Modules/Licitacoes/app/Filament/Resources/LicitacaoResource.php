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

    protected static \UnitEnum|string|null $navigationGroup = 'Licitações';

    protected static ?string $navigationLabel = 'Gerenciar Pregões';

    protected static ?string $modelLabel = 'Pregão';

    protected static ?string $pluralModelLabel = 'Gerenciar Pregões';

    protected static ?string $slug = 'pregoes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações do Pregão')
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
                        Forms\Components\TextInput::make('uasg')
                            ->label('UASG')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('local_disputa')
                            ->label('Local da Disputa')
                            ->maxLength(255),
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
                            ->required(),
                        Forms\Components\DatePicker::make('data_disputa')
                            ->label('Data de Abertura/Disputa'),
                        Forms\Components\TimePicker::make('hora_disputa')
                            ->label('Hora da Disputa'),
                        
                        Forms\Components\Textarea::make('objeto')
                            ->label('Objeto')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),

                \Filament\Schemas\Components\Section::make('Órgão Comprador')
                    ->schema([
                        Forms\Components\TextInput::make('orgao_cnpj')
                            ->label('CNPJ')
                            ->mask('99.999.999/9999-99')
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set) {
                                if (!$state) return;
                                $cnpj = preg_replace('/[^0-9]/', '', $state);
                                if (strlen($cnpj) !== 14) return;
                                try {
                                    $response = \Illuminate\Support\Facades\Http::get("https://brasilapi.com.br/api/cnpj/v1/{$cnpj}");
                                    if ($response->successful()) {
                                        $data = $response->json();
                                        $set('orgao_razao_social', $data['razao_social'] ?? null);
                                        $set('orgao_nome_fantasia', $data['nome_fantasia'] ?? null);
                                        $endereco = trim(($data['logradouro'] ?? '') . ' ' . ($data['numero'] ?? '') . ' ' . ($data['complemento'] ?? ''));
                                        $set('orgao_endereco', $endereco);
                                        $set('orgao_bairro', $data['bairro'] ?? null);
                                        $set('orgao_cidade', $data['municipio'] ?? null);
                                        $set('orgao_estado', $data['uf'] ?? null);
                                        if (isset($data['cep'])) {
                                            $cep = preg_replace('/^(\d{5})(\d{3})$/', '$1-$2', $data['cep']);
                                            $set('orgao_cep', $cep);
                                        }
                                    } else {
                                        \Filament\Notifications\Notification::make()->title('CNPJ não encontrado')->danger()->send();
                                    }
                                } catch (\Exception $e) {
                                    \Filament\Notifications\Notification::make()->title('Erro ao buscar CNPJ')->danger()->send();
                                }
                            }),
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
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações do Pregão')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('numero_edital')
                            ->label('Edital'),
                        \Filament\Infolists\Components\TextEntry::make('numero_processo')
                            ->label('Processo'),
                        \Filament\Infolists\Components\TextEntry::make('modalidade')
                            ->label('Modalidade'),
                        \Filament\Infolists\Components\TextEntry::make('orgao_razao_social')
                            ->label('Órgão Comprador'),
                        \Filament\Infolists\Components\TextEntry::make('local_disputa')
                            ->label('Local da Disputa'),
                        \Filament\Infolists\Components\TextEntry::make('uasg')
                            ->label('UASG'),
                        \Filament\Infolists\Components\TextEntry::make('data_disputa')
                            ->label('Data da Disputa')
                            ->date('d/m/Y'),
                        \Filament\Infolists\Components\TextEntry::make('hora_disputa')
                            ->label('Hora da Disputa'),
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                        \Filament\Infolists\Components\TextEntry::make('objeto')
                            ->label('Objeto')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('numero_edital')
                            ->weight('bold')
                            ->color('primary')
                            ->searchable()
                            ->formatStateUsing(fn ($state) => "#" . $state),
                        
                        Tables\Columns\TextColumn::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Em análise' => 'warning',
                                'Acolhimento de propostas' => 'info',
                                'Homologado' => 'success',
                                'Adjudicado' => 'success',
                                'Revogado' => 'danger',
                                'Fracassado' => 'danger',
                                'Anulado' => 'danger',
                                'Suspenso' => 'gray',
                                default => 'primary',
                            }),
                    ]),
                    
                    Tables\Columns\TextColumn::make('orgao_razao_social')
                        ->size('lg')
                        ->weight('bold')
                        ->searchable()
                        ->wrap(),
                        
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('orgao_cidade')
                            ->icon('heroicon-o-map-pin')
                            ->color('gray')
                            ->formatStateUsing(fn ($record) => $record->orgao_cidade . '/' . $record->orgao_estado),
                            
                        Tables\Columns\TextColumn::make('data_disputa')
                            ->icon('heroicon-o-calendar')
                            ->color('gray')
                            ->date('d/m/Y'),
                            
                        Tables\Columns\TextColumn::make('modalidade')
                            ->icon('heroicon-o-tag')
                            ->color('gray'),
                    ])->from('md'),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
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
                \Filament\Actions\Action::make('gerar_proposta')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('licitacoes::pdf.proposta', ['licitacao' => $record]);
                        $filename = 'proposta-' . str_replace(['/', '\\'], '-', $record->numero_edital) . '.pdf';
                        return response()->streamDownload(fn () => print($pdf->output()), $filename);
                    }),
                \Filament\Actions\ViewAction::make()->label('Detalhes')->button()->color('primary'),
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
            LicitacaoResource\RelationManagers\ItensRelationManager::class,
            LicitacaoResource\RelationManagers\AnexosRelationManager::class,
            LicitacaoResource\RelationManagers\DocumentosRelationManager::class,
            LicitacaoResource\RelationManagers\ObservacoesRelationManager::class,
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

