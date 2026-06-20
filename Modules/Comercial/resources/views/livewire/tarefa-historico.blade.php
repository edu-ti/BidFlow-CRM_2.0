<div class="mt-4">
    <div class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
        <x-heroicon-o-clock class="w-5 h-5"/>
        Histórico e Anotações
    </div>
    
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
        <textarea wire:model.defer="nova_nota" 
            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
            rows="3" 
            placeholder="Adicionar nova atualização de andamento..."></textarea>
        
        <div class="flex justify-end mt-2">
            <button wire:click="salvarNota" 
                class="bg-primary-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-primary-700 transition text-sm font-medium">
                Salvar Nota
            </button>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($historicos as $historico)
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-100 dark:border-gray-800">
                <div class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $historico->nota }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex justify-between">
                    <span>{{ $historico->created_at->format('d/m/Y H:i') }}</span>
                    @if($historico->oportunidade_id && !$historico->tarefa_agenda_id)
                        <span class="text-[10px] bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 px-2 py-0.5 rounded">Via Oportunidade</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-sm text-center text-gray-500 dark:text-gray-400 py-4">
                Nenhuma anotação ou histórico encontrado.
            </div>
        @endforelse
    </div>
</div>
