<?php

namespace Modules\Comercial\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Comercial\Models\Produto;
use Modules\Comercial\Filament\Resources\ProdutoResource\Pages;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cube';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $pluralModelLabel = 'Catálogo de Produtos';

    protected static ?string $slug = 'catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Informações do Produto')
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome do Produto')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('fabricante')
                            ->label('Fabricante')
                            ->options(\Modules\Fornecedores\Models\Fornecedor::all()->mapWithKeys(function ($fornecedor) {
                                $nome = !empty($fornecedor->nome_fantasia) ? $fornecedor->nome_fantasia : $fornecedor->razao_social;
                                return [$nome => $nome];
                            })->sort())
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('modelo')
                            ->label('Modelo')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unidade')
                            ->label('Unidade de Medida')
                            ->default('UN')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('valor_unitario')
                            ->label('Valor Unitário (R$)')
                            ->numeric()
                            ->default(0)
                            ->prefix('R$'),
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('imagem_path')
                            ->label('Imagem')
                            ->image()
                            ->directory('produtos')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Detalhes do Produto')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('nome')->label('Nome'),
                        \Filament\Infolists\Components\TextEntry::make('fabricante')->label('Fabricante'),
                        \Filament\Infolists\Components\TextEntry::make('modelo')->label('Modelo'),
                        \Filament\Infolists\Components\TextEntry::make('unidade')->label('Unidade'),
                        \Filament\Infolists\Components\TextEntry::make('valor_unitario')
                            ->label('Valor Unitário')
                            ->money('BRL'),
                        \Filament\Infolists\Components\TextEntry::make('descricao')->label('Descrição')->columnSpanFull(),
                        \Filament\Infolists\Components\ImageEntry::make('imagem_path')->label('Imagem')->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem_path')
                    ->label('Imagem')
                    ->square(),
                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fabricante')
                    ->label('Fabricante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modelo')
                    ->label('Modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unidade')
                    ->label('UN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor_unitario')
                    ->label('Valor Unit.')
                    ->money('BRL')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProdutos::route('/'),
            'create' => Pages\CreateProduto::route('/create'),
            'view' => Pages\ViewProduto::route('/{record}'),
            'edit' => Pages\EditProduto::route('/{record}/edit'),
        ];
    }
}
