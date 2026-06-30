@props(['licitacao', 'index' => null, 'isGerenciada' => false])

<div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden; margin-bottom: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
    
    <!-- Header (Dark bar) -->
    <div style="background-color: #1e293b; color: white; padding: 0.5rem 1rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            @if($index !== null)
                <span style="background-color: #10b981; color: white; font-weight: bold; font-size: 0.75rem; width: 1.5rem; height: 1.5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                    {{ $index }}
                </span>
            @endif
            
            <button wire:click="toggleFavorito({{ $licitacao->id }})" style="color: {{ $licitacao->favorito ? '#fbbf24' : '#94a3b8' }}; cursor: pointer; border: none; background: transparent;">
                <x-filament::icon icon="{{ $licitacao->favorito ? 'heroicon-s-star' : 'heroicon-o-star' }}" style="width: 1.25rem; height: 1.25rem;" />
            </button>
            
            @if(!$isGerenciada)
                <x-filament::icon icon="heroicon-o-eye" style="width: 1.25rem; height: 1.25rem; color: #94a3b8;" />
            @endif
        </div>
        
        <div style="display: flex; gap: 0.5rem;">
            @if($licitacao->status_badge)
                <span style="background-color: {{ $licitacao->status_cor ?? '#10b981' }}; color: white; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: bold; text-transform: uppercase;">
                    {{ $licitacao->status_badge }}
                </span>
            @endif
        </div>
    </div>
    
    <!-- Body -->
    <div style="padding: 1rem;">
        <div style="margin-bottom: 1rem; font-size: 0.875rem; color: #374151;">
            <span style="font-weight: 600;">Objeto:</span> {{ $licitacao->objeto }}
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.75rem; color: #4b5563; margin-bottom: 1rem;">
            <div>
                <span style="font-weight: 600;">Datas:</span> Abertura: {{ ($licitacao->data_abertura ?? $licitacao->data_disputa) ? ($licitacao->data_abertura ?? $licitacao->data_disputa)->format('d/m/Y H:i') : 'Não informada' }}
            </div>
            <div>
                <span style="font-weight: 600;">Órgão:</span> <span style="color: #2563eb;">{{ $licitacao->uasg ? $licitacao->uasg . ' - ' : '' }}{{ $licitacao->orgao ?? $licitacao->orgao_razao_social }}</span>
            </div>
            <div>
                <span style="font-weight: 600;">Cidade:</span> {{ $licitacao->cidade ?? $licitacao->orgao_cidade }} - {{ $licitacao->estado ?? $licitacao->orgao_estado }}
            </div>
            <div>
                <span style="font-weight: 600;">Edital:</span> {{ $licitacao->edital ?? $licitacao->numero_edital }}
            </div>
            @if($isGerenciada && $licitacao->valor_estimado)
                <div>
                    <span style="font-weight: 600;">Valor Estimado:</span> <span style="color: #ea580c;">R$ {{ number_format($licitacao->valor_estimado, 2, ',', '.') }}</span>
                </div>
                <div>
                    <span style="font-weight: 600;">Link:</span> <a href="{{ $licitacao->link_detalhes }}" target="_blank" style="color: #2563eb;">Acessar local da disputa</a>
                </div>
            @endif
        </div>
        
        <div style="margin-bottom: 1rem;">
            <a href="#" style="color: #2563eb; font-size: 0.75rem; font-weight: 500;">Ver mais informações da licitação</a>
        </div>
        
        <!-- Ações -->
        <div style="margin-bottom: 1rem;">
            <div style="font-size: 0.75rem; font-weight: 600; margin-bottom: 0.25rem;">Ações:</div>
            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                
                @if($isGerenciada)
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-chat-bubble-left-ellipsis" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Desativar monitoramento de chat</x-filament::button>
                    <x-filament::button size="sm" color="gray" icon="heroicon-o-folder-arrow-down" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Ver arquivos</x-filament::button>
                @else
                    <x-filament::button size="sm" color="primary" wire:click="gerenciarLicitacao({{ $licitacao->id }})" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Gerenciar licitação</x-filament::button>
                    <x-filament::button size="sm" color="primary" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">Ativar monitoramento de chat</x-filament::button>
                @endif
            </div>
        </div>
        
        <!-- Anotações -->
        <div>
            <div style="font-size: 0.75rem; font-weight: 600; margin-bottom: 0.25rem;">Anotações</div>
            <div style="display: flex; align-items: center; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.25rem 0.5rem; background: white;">
                <input type="text" placeholder="Digite uma mensagem" style="border: none; outline: none; width: 100%; font-size: 0.875rem;" />
                <x-filament::icon icon="heroicon-o-microphone" style="width: 1.25rem; height: 1.25rem; color: #9ca3af; cursor: pointer; margin: 0 0.5rem;" />
                <button style="background: #0ea5e9; border: none; border-radius: 0.25rem; padding: 0.25rem; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <x-filament::icon icon="heroicon-o-paper-airplane" style="width: 1rem; height: 1rem; color: white;" />
                </button>
            </div>
        </div>
        
    </div>
    
    <!-- Footer -->
    <div style="background-color: #f9fafb; border-top: 1px solid #e5e7eb; padding: 0.5rem 1rem; display: flex; justify-content: space-between; align-items: center; font-size: 0.625rem; color: #6b7280;">
        <div style="display: flex; align-items: center; gap: 0.25rem;">
            Nº Conlicitação: {{ $licitacao->conlicitacao ?? $licitacao->id }}
            <x-filament::icon icon="heroicon-o-document-duplicate" style="width: 0.75rem; height: 0.75rem; cursor: pointer;" />
        </div>
        <div>
            Atualizada em: {{ $licitacao->updated_at ? $licitacao->updated_at->format('d/m/Y \à\s H:i') : now()->format('d/m/Y \à\s H:i') }}
        </div>
    </div>
</div>
