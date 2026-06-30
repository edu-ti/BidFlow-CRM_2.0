<?php

namespace Modules\Licitacoes\Filament\Resources;

use Modules\Licitacoes\Filament\Resources\OportunidadeLicitacaoResource\Pages;
use Modules\Licitacoes\Models\OportunidadeLicitacao;
use Modules\Licitacoes\Models\Licitacao;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class OportunidadeLicitacaoResource extends Resource
{
    protected static ?string $model = OportunidadeLicitacao::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-magnifying-glass';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Licitações';
    
    protected static ?string $modelLabel = 'Oportunidade';
    
    protected static ?string $pluralModelLabel = 'Encontrar Licitações';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            // Geralmente Oportunidades são read-only no form
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('edital')
                            ->weight('bold')
                            ->color('primary')
                            ->searchable()
                            ->formatStateUsing(fn ($state) => "#" . $state),
                        
                        Tables\Columns\TextColumn::make('status_badge')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'NOVA' => 'success',
                                'RETIFICAÇÃO' => 'warning',
                                'URGENTE' => 'danger',
                                default => 'gray',
                            }),
                        
                        Tables\Columns\TextColumn::make('data_publicacao')
                            ->date('d/m/Y')
                            ->icon('heroicon-o-calendar')
                            ->color('gray')
                            ->size('sm'),
                            
                        Tables\Columns\TextColumn::make('data_abertura')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-o-clock')
                            ->color('gray')
                            ->size('sm'),
                    ]),
                    
                    Tables\Columns\TextColumn::make('objeto')
                        ->size('lg')
                        ->weight('bold')
                        ->searchable()
                        ->wrap(),
                        
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('orgao')
                            ->icon('heroicon-o-building-office-2')
                            ->color('gray')
                            ->searchable(),
                            
                        Tables\Columns\TextColumn::make('cidade')
                            ->icon('heroicon-o-map-pin')
                            ->color('gray')
                            ->formatStateUsing(fn ($record) => $record->cidade . '/' . $record->estado),
                            
                        Tables\Columns\TextColumn::make('valor_estimado')
                            ->money('BRL')
                            ->icon('heroicon-o-currency-dollar')
                            ->color('success')
                            ->weight('bold'),
                    ])->from('md'),
                    
                    Tables\Columns\Layout\Split::make([
                        Tables\Columns\TextColumn::make('modalidade')
                            ->badge()
                            ->color('gray')
                            ->icon('heroicon-o-tag'),
                            
                        Tables\Columns\TextColumn::make('uasg')
                            ->prefix('UASG: ')
                            ->color('gray'),
                            
                        Tables\Columns\TextColumn::make('conlicitacao')
                            ->prefix('Conlicitação: ')
                            ->color('gray'),
                    ])->from('md'),
                    
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 1,
            ])
            ->filters([
                Tables\Filters\Filter::make('apenas_novas')
                    ->label('Apenas Novas')
                    ->query(fn (Builder $query): Builder => $query->where('status_badge', 'NOVA')),
                    
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas', 
                        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 
                        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 
                        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 
                        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 
                        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina', 
                        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                    ])
                    ->searchable(),
                    
                Tables\Filters\SelectFilter::make('modalidade')
                    ->options([
                        'Pregão Eletrônico' => 'Pregão Eletrônico',
                        'Pregão Presencial' => 'Pregão Presencial',
                        'Concorrência' => 'Concorrência',
                        'Dispensa' => 'Dispensa',
                    ]),
            ], layout: FiltersLayout::Dropdown)
            ->actions([
                \Filament\Actions\Action::make('detalhes')
                    ->label('Ver itens')
                    ->icon('heroicon-o-list-bullet')
                    ->color('primary')
                    ->url(fn ($record) => $record->link_detalhes, shouldOpenInNewTab: true),
                    
                \Filament\Actions\Action::make('gerenciar')
                    ->label('Gerenciar')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('success')
                    ->action(function ($record) {
                        // Promove a Oportunidade para Licitação
                        $novaLicitacao = Licitacao::create([
                            'numero_edital' => $record->edital,
                            'numero_processo' => $record->processo,
                            'modalidade' => $record->modalidade,
                            'orgao_razao_social' => $record->orgao,
                            'estado' => $record->estado,
                            'cidade' => $record->cidade,
                            'uasg' => $record->uasg,
                            'data_disputa' => $record->data_abertura ? $record->data_abertura->format('Y-m-d') : null,
                            'hora_disputa' => $record->data_abertura ? $record->data_abertura->format('H:i') : null,
                            'status' => 'Em análise',
                        ]);
                        
                        $record->update(['gerenciada' => true]);
                        
                        Notification::make()
                            ->title('Importado com sucesso!')
                            ->body('A licitação agora está no seu painel de gestão.')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn ($record) => $record->gerenciada),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn (OportunidadeLicitacao $record) => match ($record->status_badge) {
                'NOVA' => 'border-l-4 border-l-success-500',
                'URGENTE' => 'border-l-4 border-l-danger-500',
                'RETIFICAÇÃO' => 'border-l-4 border-l-warning-500',
                default => '',
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOportunidadeLicitacaos::route('/'),
        ];
    }
}
