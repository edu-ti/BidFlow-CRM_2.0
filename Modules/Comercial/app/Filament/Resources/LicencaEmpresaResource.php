<?php

namespace Modules\Comercial\Filament\Resources;

use Modules\Comercial\Filament\Resources\LicencaEmpresaResource\Pages;
use Modules\Comercial\Models\LicencaEmpresa;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class LicencaEmpresaResource extends Resource
{
    protected static ?string $model = LicencaEmpresa::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Licenças e Certidões';
    protected static ?string $modelLabel = 'Licença/Certidão';
    protected static ?string $pluralModelLabel = 'Licenças e Certidões';
    protected static \UnitEnum|string|null $navigationGroup = 'Visão Geral da Empresa';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título do Documento')
                    ->placeholder('Ex: Alvará de Localização, CNDT...')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Checkbox::make('sem_validade')
                    ->label('Não tem validade (prazo indeterminado)')
                    ->reactive()
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('data_vencimento')
                    ->label('Data de Vencimento')
                    ->disabled(fn ($get) => $get('sem_validade') === true)
                    ->required(fn ($get) => $get('sem_validade') !== true)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('arquivo_path')
                    ->label('Arquivo (PDF, imagem)')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                    ->disk('public')
                    ->directory('licencas-empresa')
                    ->downloadable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Documento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_vencimento')
                    ->label('Vencimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->sem_validade) {
                            return 'Sem validade';
                        }
                        return $state ? \Carbon\Carbon::parse($state)->format('d/m/Y') : '-';
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->sem_validade) {
                            return 'gray';
                        }
                        if (!$record->data_vencimento) {
                            return 'gray';
                        }
                        
                        $hoje = Carbon::today();
                        $vencimento = Carbon::parse($record->data_vencimento);
                        
                        if ($vencimento->isPast()) {
                            return 'danger';
                        }
                        if ($hoje->diffInDays($vencimento, false) <= 30) {
                            return 'warning';
                        }
                        
                        return 'success';
                    })
                    ->getStateUsing(function ($record) {
                        if ($record->sem_validade) {
                            return 'Prazo indeterminado';
                        }
                        if (!$record->data_vencimento) {
                            return '-';
                        }

                        $hoje = Carbon::today();
                        $vencimento = Carbon::parse($record->data_vencimento);
                        
                        if ($vencimento->isPast()) {
                            $dias = $hoje->diffInDays($vencimento);
                            return 'Vencido há ' . $dias . ' dia(s)';
                        }
                        
                        $dias = $hoje->diffInDays($vencimento);
                        return 'Vence em ' . $dias . ' dia(s)';
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\Action::make('download')
                    ->label('Ver Anexo')
                    ->icon('heroicon-o-eye')
                    ->url(fn ($record) => $record->arquivo_path ? asset('storage/' . $record->arquivo_path) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->arquivo_path)),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageLicencaEmpresas::route('/'),
        ];
    }
}
