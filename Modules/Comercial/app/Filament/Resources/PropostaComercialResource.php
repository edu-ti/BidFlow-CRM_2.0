<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\PropostaComercial;
use Modules\Comercial\Filament\Resources\PropostaComercialResource\Pages;

class PropostaComercialResource extends Resource
{
    protected static ?string $model = PropostaComercial::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Proposta Comercial';

    protected static ?string $pluralModelLabel = 'Propostas Comerciais';

    protected static ?string $slug = 'propostas-comerciais';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações da Proposta')
                    ->schema([
                        Forms\Components\TextInput::make('numero')
                            ->label('Número da Proposta')
                            ->default(fn () => 'PROP-' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('fornecedor_id')
                            ->label('Cliente')
                            ->relationship('fornecedor', 'razao_social')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('oportunidade_id')
                            ->label('Oportunidade Vinculada')
                            ->relationship('oportunidade', 'titulo')
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('data_proposta')
                            ->label('Data da Proposta')
                            ->default(now()),
                        Forms\Components\DatePicker::make('validade')
                            ->label('Validade'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'Em elaboração' => 'Em elaboração',
                                'Enviada' => 'Enviada',
                                'Em Negociação' => 'Em Negociação',
                                'Aprovada' => 'Aprovada',
                                'Recusada' => 'Recusada',
                            ])
                            ->default('Em elaboração')
                            ->required(),
                        Forms\Components\Textarea::make('observacoes')
                            ->label('Observações')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Itens da Proposta')
                    ->schema([
                        Forms\Components\Repeater::make('itens')
                            ->relationship()
                            ->schema([
                                \Filament\Schemas\Components\Grid::make(6)->schema([
                                    Forms\Components\Select::make('produto_id')
                                        ->label('Produto (Opcional - Selecione para preencher dados do catálogo)')
                                        ->relationship('produto', 'nome')
                                        ->searchable()
                                        ->preload()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            if ($state) {
                                                $produto = \Modules\Comercial\Models\Produto::find($state);
                                                if ($produto) {
                                                    $set('descricao', $produto->nome);
                                                    $set('fabricante', $produto->fabricante);
                                                    $set('modelo', $produto->modelo);
                                                    
                                                    $isLocacao = $get('tipo') === 'Locação';
                                                    $valorCalc = $isLocacao ? round((float)($produto->valor_unitario ?: 0) * 0.06, 2) : (float)($produto->valor_unitario ?: 0);
                                                    $set('valor_unitario', $valorCalc);
                                                    
                                                    $set('unidade_medida', $produto->unidade ?? 'Unidade');
                                                    $set('imagem', $produto->imagem_path);
                                                    
                                                    $q = (float)($get('quantidade') ?: 0);
                                                    $v = $valorCalc;
                                                    $d = (float)($get('desconto_percentual') ?: 0);
                                                    $m = $isLocacao ? (float)($get('meses_locacao') ?: 1) : 1;
                                                    $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                                }
                                            }
                                        })
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('descricao')
                                        ->label('Descrição')
                                        ->required()
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('fabricante')
                                        ->label('Fabricante')
                                        ->columnSpan(2),
                                    Forms\Components\Placeholder::make('imagem_preview')
                                        ->label('Visualização da Imagem')
                                        ->content(function (callable $get) {
                                            $path = $get('imagem');
                                            if ($path) {
                                                $disk = \Illuminate\Support\Facades\Storage::disk('local');
                                                if ($disk->exists($path)) {
                                                    $mime = $disk->mimeType($path);
                                                    $data = base64_encode($disk->get($path));
                                                    return new \Illuminate\Support\HtmlString('<img src="data:' . $mime . ';base64,' . $data . '" style="max-height: 150px; border-radius: 8px; border: 1px solid #e5e7eb;">');
                                                }
                                                // Se não achar no local, tenta no public
                                                $diskPub = \Illuminate\Support\Facades\Storage::disk('public');
                                                if ($diskPub->exists($path)) {
                                                    return new \Illuminate\Support\HtmlString('<img src="/storage/' . $path . '" style="max-height: 150px; border-radius: 8px; border: 1px solid #e5e7eb;">');
                                                }
                                            }
                                            return 'Sem imagem selecionada';
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\FileUpload::make('imagem')
                                        ->label('Substituir/Enviar Imagem')
                                        ->image()
                                        ->disk('public')
                                        ->directory('produtos')
                                        ->columnSpan(1),
                                ]),
                                \Filament\Schemas\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('modelo')
                                        ->label('Modelo')
                                        ->columnSpan(1),
                                    Forms\Components\Select::make('tipo')
                                        ->label('Tipo')
                                        ->options([
                                            'Venda' => 'Venda',
                                            'Locação' => 'Locação',
                                        ])
                                        ->default('Venda')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $produtoId = $get('produto_id');
                                            $v = (float)($get('valor_unitario') ?: 0);
                                            
                                            if ($produtoId) {
                                                $produto = \Modules\Comercial\Models\Produto::find($produtoId);
                                                if ($produto) {
                                                    if ($state === 'Locação') {
                                                        $v = round((float)($produto->valor_unitario ?: 0) * 0.06, 2);
                                                    } else {
                                                        $v = (float)($produto->valor_unitario ?: 0);
                                                    }
                                                    $set('valor_unitario', $v);
                                                }
                                            }
                                            
                                            $q = (float)($get('quantidade') ?: 0);
                                            $d = (float)($get('desconto_percentual') ?: 0);
                                            $m = $state === 'Locação' ? (float)($get('meses_locacao') ?: 1) : 1;
                                            $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\Textarea::make('descricao_detalhada')
                                        ->label('Descrição Detalhada')
                                        ->columnSpanFull(),
                                ]),
                                
                                Forms\Components\Repeater::make('parametros_adicionais')
                                    ->label('Parâmetros Adicionais')
                                    ->schema([
                                        Forms\Components\TextInput::make('nome')->label('Nome do Parâmetro')->required(),
                                        Forms\Components\TextInput::make('valor')->label('Valor do Parâmetro')->required(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->defaultItems(0),

                                \Filament\Schemas\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('quantidade')
                                        ->label('Quantidade')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $q = (float)($state ?: 0);
                                            $v = (float)($get('valor_unitario') ?: 0);
                                            $d = (float)($get('desconto_percentual') ?: 0);
                                            $m = $get('tipo') === 'Locação' ? (float)($get('meses_locacao') ?: 1) : 1;
                                            $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('valor_unitario')
                                        ->label('Valor Unitário')
                                        ->numeric()
                                        ->default(0)
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $v = (float)($state ?: 0);
                                            $q = (float)($get('quantidade') ?: 0);
                                            $d = (float)($get('desconto_percentual') ?: 0);
                                            $m = $get('tipo') === 'Locação' ? (float)($get('meses_locacao') ?: 1) : 1;
                                            $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('meses_locacao')
                                        ->label('Meses Locação')
                                        ->numeric()
                                        ->default(12)
                                        ->visible(fn(callable $get) => $get('tipo') === 'Locação')
                                        ->required(fn(callable $get) => $get('tipo') === 'Locação')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $m = (float)($state ?: 1);
                                            $q = (float)($get('quantidade') ?: 0);
                                            $v = (float)($get('valor_unitario') ?: 0);
                                            $d = (float)($get('desconto_percentual') ?: 0);
                                            $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('unidade_medida')
                                        ->label('Unidade de Medida')
                                        ->default('Unidade')
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('desconto_percentual')
                                        ->label('Desconto (%)')
                                        ->numeric()
                                        ->default(0)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                            $d = (float)($state ?: 0);
                                            $q = (float)($get('quantidade') ?: 0);
                                            $v = (float)($get('valor_unitario') ?: 0);
                                            $m = $get('tipo') === 'Locação' ? (float)($get('meses_locacao') ?: 1) : 1;
                                            $set('valor_total', round(($q * $v * $m) * (1 - ($d / 100)), 2));
                                        })
                                        ->columnSpan(1),
                                    Forms\Components\TextInput::make('valor_total')
                                        ->label('Subtotal')
                                        ->numeric()
                                        ->disabled()
                                        ->dehydrated()
                                        ->default(0)
                                        ->columnSpan(1),
                                ]),
                            ])
                            ->columnSpanFull()
                    ]),

                \Filament\Schemas\Components\Section::make('Frete')
                    ->schema([
                        Forms\Components\Select::make('tipo_frete')
                            ->label('Frete*')
                            ->options([
                                'CIF (Pago pelo Remetente)' => 'CIF (Pago pelo Remetente)',
                                'FOB (Pago pelo Destinatário)' => 'FOB (Pago pelo Destinatário)',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('valor_frete')
                            ->label('Valor do Frete')
                            ->numeric()
                            ->prefix('R$')
                            ->default(0),
                    ])->columns(2),

                \Filament\Schemas\Components\Section::make('Termos Comerciais')
                    ->schema([
                        Forms\Components\Textarea::make('termos_comerciais.faturamento')
                            ->label('Faturamento')
                            ->default('Realizado diretamente pela fábrica.'),
                        Forms\Components\Textarea::make('termos_comerciais.treinamento')
                            ->label('Treinamento')
                            ->default('Capacitação técnica por especialistas da empresa.'),
                        Forms\Components\Textarea::make('termos_comerciais.condicoes_pagamento')
                            ->label('Condições de Pagamento')
                            ->default('A vista'),
                        Forms\Components\Textarea::make('termos_comerciais.prazo_entrega')
                            ->label('Prazo de Entrega')
                            ->default('Até 30 dias após a confirmação do pedido de compra.'),
                        Forms\Components\Textarea::make('termos_comerciais.garantia_equipamentos')
                            ->label('Garantia (Equipamentos)')
                            ->default('12 meses a partir da data de emissão da nota fiscal.'),
                        Forms\Components\Textarea::make('termos_comerciais.garantia_acessorios')
                            ->label('Garantia (Acessórios)')
                            ->default('6 meses, conforme especificações do fabricante.'),
                        Forms\Components\Textarea::make('termos_comerciais.instalacao')
                            ->label('Instalação')
                            ->default('Realizada pela equipe técnica da empresa, garantindo conformidade e segurança.'),
                        Forms\Components\Textarea::make('termos_comerciais.assistencia_tecnica')
                            ->label('Assistência Técnica')
                            ->default('Disponível com suporte especializado para manutenção e pós garantia.'),
                        Forms\Components\Textarea::make('termos_comerciais.observacoes_termos')
                            ->label('Observações')
                            ->default('Nenhuma')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fornecedor.razao_social')
                    ->label('Cliente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_proposta')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'Em elaboração',
                        'primary' => 'Enviada',
                        'info' => 'Em Negociação',
                        'success' => 'Aprovada',
                        'danger' => 'Recusada',
                    ]),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('data_proposta')
                    ->form([
                        Forms\Components\DatePicker::make('data_de')
                            ->label('Data de'),
                        Forms\Components\DatePicker::make('data_ate')
                            ->label('Data até'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when(
                                $data['data_de'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('data_proposta', '>=', $date),
                            )
                            ->when(
                                $data['data_ate'],
                                fn (\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('data_proposta', '<=', $date),
                            );
                    }),
                \Filament\Tables\Filters\Filter::make('vendedor')
                    ->form([
                        Forms\Components\Select::make('user_id')
                            ->label('Vendedor (Usuário)')
                            ->options(\App\Models\User::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query->when(
                            $data['user_id'],
                            fn (\Illuminate\Database\Eloquent\Builder $query, $userId): \Illuminate\Database\Eloquent\Builder => $query->whereHas('oportunidade', function ($q) use ($userId) {
                                $q->where('user_id', $userId);
                            })
                        );
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('imprimir')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->button()
                    ->url(fn ($record) => route('propostas.imprimir', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPropostaComercials::route('/'),
            'create' => Pages\CreatePropostaComercial::route('/create'),
            'edit' => Pages\EditPropostaComercial::route('/{record}/edit'),
        ];
    }
}
