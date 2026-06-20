<?php

namespace Modules\Comercial\Filament\Resources\OportunidadeResource\Pages;

use Modules\Comercial\Filament\Resources\OportunidadeResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\ViewEntry;

class ViewOportunidade extends ViewRecord
{
    protected static string $resource = OportunidadeResource::class;
    protected string $view = 'comercial::filament.resources.oportunidade-resource.pages.view-oportunidade';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Resumo')
                    ->schema([
                        TextEntry::make('valor_estimado')->label('Valor')->money('BRL'),
                        TextEntry::make('probabilidade')->label('Probabilidade')->suffix('%'),
                        TextEntry::make('user.name')->label('Proprietário'),
                        TextEntry::make('data_fechamento_esperada')->label('Fechamento Esperado')->date('d/m/Y'),
                        TextEntry::make('etiquetas')->label('Etiquetas')->badge(),
                    ])->collapsible()->columns(1),

                Section::make('Pessoa')
                    ->schema([
                        TextEntry::make('pessoa_contato_nome')->label('Nome do Contato')->default('-'),
                        TextEntry::make('pessoa_contato_telefone')->label('Telefone')->default('-'),
                        TextEntry::make('pessoa_contato_email')->label('E-mail')->default('-'),
                    ])->collapsible()->columns(1),

                Section::make('Organização')
                    ->schema([
                        TextEntry::make('fornecedor.razao_social')->label('Razão Social')->default('-'),
                        TextEntry::make('fornecedor.cnpj')->label('CNPJ')->default('-'),
                    ])->collapsible()->columns(1),

                Section::make('Produtos')
                    ->schema([
                        TextEntry::make('produtos.nome')->label('Itens vinculados')->listWithLineBreaks()->bulleted(),
                    ])->collapsible()->columns(1),
            ])->columns(1);
    }
}
