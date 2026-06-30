<x-filament-panels::page>
    <div wire:poll.10s="carregarMensagens" style="display: flex; gap: 1.5rem; height: calc(100vh - 12rem); flex-wrap: wrap;">
        
        <!-- Sidebar de Licitações -->
        <div style="flex: 1; min-width: 300px; max-width: 400px; display: flex; flex-direction: column; gap: 0.5rem; overflow-y: auto; padding-right: 0.5rem;">
            @forelse($this->licitacoesAtivas as $licitacao)
                <x-filament::section 
                    wire:click="selecionarLicitacao({{ $licitacao->id }})"
                    style="cursor: pointer; transition: all 0.2s; {{ $licitacaoAtivaId === $licitacao->id ? 'box-shadow: 0 0 0 2px rgba(var(--primary-500), 1); border-color: rgba(var(--primary-500), 1);' : '' }}"
                >
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                        <span style="font-weight: bold; font-size: 1rem; color: #111827;">#{{ $licitacao->numero_edital }}</span>
                        @if($licitacao->chat_mensagens_count > 0)
                            <x-filament::badge color="danger">
                                {{ $licitacao->chat_mensagens_count }} alertas
                            </x-filament::badge>
                        @endif
                    </div>
                    <div style="font-size: 0.875rem; color: #6b7280; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $licitacao->orgao_razao_social }}
                    </div>
                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #9ca3af; display: flex; align-items: center; gap: 0.25rem;">
                        <x-filament::icon icon="heroicon-o-clock" style="width: 0.75rem; height: 0.75rem;" />
                        Disputa: {{ $licitacao->data_disputa ? \Carbon\Carbon::parse($licitacao->data_disputa)->format('d/m/Y') : 'N/A' }}
                    </div>
                </x-filament::section>
            @empty
                <div style="text-align: center; color: #6b7280; padding: 1rem;">
                    Nenhum pregão ativo no momento.
                </div>
            @endforelse
        </div>

        <!-- Área de Mensagens -->
        <x-filament::section style="flex: 2; min-width: 400px; display: flex; flex-direction: column; overflow: hidden;">
            <x-slot name="heading">
                @if($licitacaoAtivaId)
                    Chat - Edital #{{ $this->licitacoesAtivas->firstWhere('id', $licitacaoAtivaId)?->numero_edital }}
                @else
                    Monitor de Chats
                @endif
            </x-slot>
            
            <x-slot name="headerEnd">
                @if($licitacaoAtivaId)
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-arrow-path" wire:click="carregarMensagens">
                        Atualizar
                    </x-filament::button>
                @endif
            </x-slot>

            @if($licitacaoAtivaId)
                <div style="flex: 1; overflow-y: auto; padding: 1rem; display: flex; flex-direction: column; gap: 1rem; background-color: #f9fafb; border-radius: 0.5rem;">
                    @forelse($mensagens as $mensagem)
                        <div style="display: flex; flex-direction: column; {{ $mensagem->is_alert ? 'align-items: flex-end;' : 'align-items: flex-start;' }}">
                            <div style="max-width: 85%; border-radius: 0.5rem; padding: 0.75rem; border: 1px solid {{ $mensagem->is_alert ? '#fca5a5' : '#e5e7eb' }}; background-color: {{ $mensagem->is_alert ? '#fee2e2' : '#ffffff' }}; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 0.25rem;">
                                    <span style="font-size: 0.75rem; font-weight: bold; color: {{ $mensagem->is_alert ? '#b91c1c' : '#6b7280' }};">
                                        {{ $mensagem->tipo }}
                                    </span>
                                    <span style="font-size: 0.75rem; color: #9ca3af;">
                                        {{ $mensagem->data_hora->format('H:i:s') }}
                                    </span>
                                </div>
                                <div style="font-size: 0.875rem; white-space: pre-wrap; color: {{ $mensagem->is_alert ? '#7f1d1d' : '#374151' }};">
                                    {{ $mensagem->texto }}
                                </div>
                                
                                @if($mensagem->keyword_encontrada)
                                    <div style="margin-top: 0.5rem; font-size: 0.75rem; font-weight: 600; background-color: #fecaca; color: #991b1b; padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: inline-block;">
                                        Alerta de palavra: {{ $mensagem->keyword_encontrada }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #6b7280;">
                            <x-filament::icon icon="heroicon-o-chat-bubble-oval-left-ellipsis" style="width: 3rem; height: 3rem; color: #d1d5db; margin-bottom: 0.5rem;" />
                            Nenhuma mensagem neste pregão ainda.
                        </div>
                    @endforelse
                </div>
            @else
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #6b7280; padding: 3rem;">
                    <x-filament::icon icon="heroicon-o-cursor-arrow-rays" style="width: 3rem; height: 3rem; color: #d1d5db; margin-bottom: 1rem;" />
                    Selecione um pregão na lateral para monitorar o chat.
                </div>
            @endif
        </x-filament::section>
        
    </div>
</x-filament-panels::page>
