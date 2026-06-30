<x-filament-panels::page>
    <!-- Seção de Filtros -->
    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
        <form wire:submit="gerarRelatorio">
            {{ $this->form }}
            
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                <x-filament::button type="submit" color="primary">
                    Gerar Relatório
                </x-filament::button>
                <x-filament::button type="button" color="gray" wire:click="limparFiltros">
                    Limpar Filtros
                </x-filament::button>
            </div>
        </form>
    </div>

    <!-- Resultados do Relatório -->
    @if($licitacoesFiltradas !== null)
        @if($licitacoesFiltradas->isEmpty())
            <div style="text-align: center; color: #6b7280; padding: 3rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb;" class="no-print">
                Nenhuma licitação encontrada para os filtros selecionados.
            </div>
        @else
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;" class="no-print">
                <x-filament::button color="success" icon="heroicon-o-printer" onclick="window.print()">
                    Imprimir Relatório
                </x-filament::button>
            </div>
            
            <div class="relatorio-print-area">
                <div class="print-header" style="display: none; text-align: center; margin-bottom: 2rem;">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;">RELATÓRIO DE PREGÕES</h1>
                    <p style="font-size: 14px; color: #555; margin-top: 5px;">Gerado em {{ date('d/m/Y \à\s H:i') }}</p>
                    <hr style="border-top: 2px solid #000; margin-top: 10px;">
                </div>

                @foreach($licitacoesFiltradas as $record)
                    <div class="print-card" style="background: white; border-radius: 0.75rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem; border: 1px solid #e5e7eb; page-break-inside: avoid;">
                        
                        <!-- Header do Pregão -->
                        <div class="print-section-header" style="margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h1 style="font-size: 1.25rem; font-weight: bold; color: #2563eb; margin: 0;">Edital #{{ $record->numero_edital }}</h1>
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Processo: {{ $record->numero_processo ?? '-' }} | Modalidade: {{ $record->modalidade }}</p>
                            </div>
                            <div style="text-align: right;">
                                <span class="print-badge" style="display: inline-block; background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: bold; margin-bottom: 0.5rem;">
                                    {{ $record->status }}
                                </span>
                                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">Disputa: {{ $record->data_disputa ? $record->data_disputa->format('d/m/Y') : '-' }} {{ $record->hora_disputa ?? '' }}</p>
                            </div>
                        </div>

                        <!-- Dados do Órgão e Licitação (Grid) -->
                        <div class="print-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                            <!-- Órgão Comprador -->
                            <div>
                                <h2 style="font-size: 1rem; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">Dados do Órgão Comprador</h2>
                                <table style="width: 100%; font-size: 0.875rem;">
                                    <tr><td style="color: #6b7280; width: 35%; padding-bottom: 0.5rem;">Razão Social:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->orgao_razao_social }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">CNPJ:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->orgao_cnpj ?? '-' }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">Endereço:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->orgao_endereco ?? '-' }}, {{ $record->orgao_bairro ?? '-' }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">Cidade/UF:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->orgao_cidade ?? '-' }} / {{ $record->orgao_estado ?? '-' }}</td></tr>
                                </table>
                            </div>

                            <!-- Dados da Licitação -->
                            <div>
                                <h2 style="font-size: 1rem; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">Dados da Licitação</h2>
                                <table style="width: 100%; font-size: 0.875rem;">
                                    <tr><td style="color: #6b7280; width: 35%; padding-bottom: 0.5rem;">UASG:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->uasg ?? '-' }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">Local Disputa:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->local_disputa ?? '-' }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">Valor Estimado:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">R$ {{ number_format((float)($record->valor_estimado ?? 0), 2, ',', '.') }}</td></tr>
                                    <tr><td style="color: #6b7280; padding-bottom: 0.5rem;">Objeto:</td><td class="print-text-dark" style="color: #111827; font-weight: 500; padding-bottom: 0.5rem;">{{ $record->objeto }}</td></tr>
                                </table>
                            </div>
                        </div>

                        <!-- Lotes e Itens -->
                        <div>
                            <h2 style="font-size: 1rem; font-weight: bold; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem;">Lotes e Itens</h2>
                            
                            @forelse($record->itens as $item)
                                <div class="print-item-card" style="border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 1.5rem; overflow: hidden; page-break-inside: avoid;">
                                    <!-- Cabecalho do Item -->
                                    <div class="print-item-header" style="background: #f9fafb; padding: 1rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem;">
                                        <div style="display: grid; grid-template-columns: 1fr 3fr 1fr 1fr; gap: 1rem;">
                                            <div><span style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; display: block;">Lote/Item</span><span class="print-text-dark" style="font-weight: bold;">{{ $item->numero_lote ?? '-' }} / {{ $item->numero_item }}</span></div>
                                            <div><span style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; display: block;">Descrição</span><span class="print-text-dark">{{ $item->descricao }}</span></div>
                                            <div><span style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; display: block;">Qtd</span><span class="print-text-dark">{{ $item->quantidade }}</span></div>
                                            <div><span style="color: #6b7280; font-size: 0.75rem; text-transform: uppercase; display: block;">Vlr Ref.</span><span class="print-text-dark">R$ {{ number_format((float) $item->valor_unit_referencia, 2, ',', '.') }}</span></div>
                                        </div>
                                        <div class="print-item-total" style="text-align: right; margin-top: 0.5rem; color: #2563eb; font-weight: bold;">
                                            Total Ref: R$ {{ number_format(($item->quantidade * (float) $item->valor_unit_referencia), 2, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Tabela de Participantes -->
                                    <div style="overflow-x: auto;">
                                        <table class="print-table" style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.75rem;">
                                            <thead style="background: #ffffff; border-bottom: 1px solid #e5e7eb;">
                                                <tr>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Participante</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Fabricante</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Modelo</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Vlr. Unit.</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Vlr. Total</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Tipo Cota</th>
                                                    <th style="padding: 0.5rem 1rem; color: #6b7280; text-transform: uppercase; border-bottom: 2px solid #ddd;">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->participantes as $participante)
                                                    <tr style="border-bottom: 1px solid #f3f4f6;">
                                                        <td style="padding: 0.5rem 1rem; font-weight: 500; color: #000;">{{ $participante->fornecedor->nome_fantasia ?? 'Desconhecido' }}</td>
                                                        <td style="padding: 0.5rem 1rem; color: #333;">{{ $participante->fabricante_marca ?? '-' }}</td>
                                                        <td style="padding: 0.5rem 1rem; color: #333;">{{ $participante->modelo ?? '-' }}</td>
                                                        <td style="padding: 0.5rem 1rem; font-weight: 500; color: #000;">R$ {{ number_format((float) $participante->valor_unitario, 2, ',', '.') }}</td>
                                                        <td style="padding: 0.5rem 1rem; font-weight: 500; color: #000;">R$ {{ number_format(((float) $participante->valor_unitario * $item->quantidade), 2, ',', '.') }}</td>
                                                        <td style="padding: 0.5rem 1rem; color: #333;">{{ $item->tipo_cota ?? 'Principal' }}</td>
                                                        <td style="padding: 0.5rem 1rem;">
                                                            <span class="print-badge" style="background: #e0f2fe; color: #0369a1; padding: 0.125rem 0.5rem; border-radius: 9999px; font-weight: bold;">
                                                                {{ $participante->status }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" style="padding: 1rem; text-align: center; color: #9ca3af;">Nenhuma proposta ou participante registrado para este item.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align: center; padding: 2rem; color: #9ca3af; border: 1px dashed #d1d5db; border-radius: 0.5rem;">
                                    Nenhum item ou lote cadastrado para este pregão.
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Estilo para impressão -->
            <style>
                @media print {
                    /* Técnica para esconder a interface e manter apenas o relatório */
                    body * {
                        visibility: hidden;
                    }
                    form, .no-print {
                        display: none !important;
                    }

                    /* Tornar apenas a área do relatório e seus filhos visíveis */
                    .relatorio-print-area, .relatorio-print-area * {
                        visibility: visible;
                    }

                    /* Garantir que nenhum contêiner pai atrapalhe o posicionamento absoluto */
                    html, body, .fi-layout, .fi-main, .fi-page {
                        position: static !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        transform: none !important;
                        overflow: visible !important;
                        height: auto !important;
                        min-height: 0 !important;
                    }
                    
                    /* Posicionar o relatório no topo absoluto da folha de papel */
                    .relatorio-print-area {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        background: white !important;
                        color: black !important;
                        margin: 0 !important;
                        padding: 0 !important;
                    }

                    /* Mostra o cabeçalho oficial do relatório */
                    .print-header {
                        display: block !important;
                        margin-bottom: 2rem !important;
                        page-break-after: avoid;
                    }

                    /* Remove sombras, fundos arredondados e borders de UI web */
                    .print-card {
                        box-shadow: none !important;
                        border-radius: 0 !important;
                        border: none !important;
                        border-bottom: 2px dashed #ccc !important;
                        padding: 0 !important;
                        margin-bottom: 20px !important;
                        padding-bottom: 20px !important;
                        page-break-inside: avoid;
                    }
                    .print-section-header {
                        border-bottom: 1px solid #000 !important;
                    }
                    .print-section-header h1 {
                        color: #000 !important;
                        font-size: 16pt !important;
                    }
                    .print-badge {
                        background: transparent !important;
                        border: 1px solid #000 !important;
                        color: #000 !important;
                        border-radius: 0 !important;
                        padding: 2px 4px !important;
                    }
                    .print-item-card {
                        border: 1px solid #000 !important;
                        border-radius: 0 !important;
                        margin-bottom: 20px !important;
                        page-break-inside: avoid;
                    }
                    .print-item-header {
                        background: #f8f8f8 !important;
                        border-bottom: 1px solid #000 !important;
                    }
                    .print-text-dark {
                        color: #000 !important;
                    }
                    .print-item-total {
                        color: #000 !important;
                    }
                    
                    /* Melhora as bordas da tabela e cores para preto no branco */
                    .print-table th, .print-table td {
                        border-bottom: 1px solid #ccc !important;
                        color: #000 !important;
                    }
                    .print-table th {
                        border-bottom: 2px solid #000 !important;
                    }

                    @page {
                        margin: 1cm;
                        size: A4 portrait;
                    }
                }
            </style>
        @endif
    @endif
</x-filament-panels::page>
