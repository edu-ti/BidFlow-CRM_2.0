<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\Onboarding;

class OnboardingResource extends Resource
{
    protected static ?string $model = Onboarding::class;
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-rocket-launch';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected static ?string $modelLabel = 'Projeto (Onboarding)';
    protected static ?string $pluralModelLabel = 'Onboarding (Lista)';
    protected static ?string $slug = 'onboardings';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Resumo da Venda')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')->label('Projeto')->disabled(),
                        Forms\Components\Select::make('fornecedor_id')->label('Cliente')->relationship('fornecedor', 'razao_social')->disabled(),
                        Forms\Components\TextInput::make('valor_fechado')->label('Valor Fechado')->numeric()->disabled(),
                        Forms\Components\DateTimePicker::make('data_venda')->label('Data da Venda')->disabled(),
                        Forms\Components\Textarea::make('resumo_venda')->label('Briefing de Vendas')->disabled()->columnSpanFull(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Execução do CS')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Fase do Onboarding')
                            ->options([
                                'Transição de Vendas' => 'Transição de Vendas',
                                'Reunião de Alinhamento' => 'Reunião de Alinhamento',
                                'Controle de Entrega' => 'Controle de Entrega',
                                'Treinamentos' => 'Treinamentos',
                                'Pós-venda' => 'Pós-venda',
                            ])->required(),
                        Forms\Components\DateTimePicker::make('data_conclusao_esperada')->label('Data de Conclusão Esperada'),
                        Forms\Components\DateTimePicker::make('data_conclusao_real')->label('Concluído em'),
                        Forms\Components\Textarea::make('anotacoes_cs')->label('Anotações do CS')->columnSpanFull()->rows(5),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')->label('Projeto')->searchable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')->label('Cliente')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Fase')->badge(),
                Tables\Columns\TextColumn::make('valor_fechado')->label('Valor')->money('BRL'),
                Tables\Columns\TextColumn::make('data_venda')->label('Data da Venda')->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                                'Transição de Vendas' => 'Transição de Vendas',
                                'Reunião de Alinhamento' => 'Reunião de Alinhamento',
                                'Controle de Entrega' => 'Controle de Entrega',
                                'Treinamentos' => 'Treinamentos',
                                'Pós-venda' => 'Pós-venda',
                ]),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()->button()->color('primary'),
            ]);
    }
}
