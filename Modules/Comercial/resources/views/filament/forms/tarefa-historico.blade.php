@if($getRecord())
    @livewire(\Modules\Comercial\Livewire\TarefaHistorico::class, [
        'tarefa_agenda_id' => $getRecord()->id, 
        'oportunidade_id' => $getRecord()->oportunidade_id
    ])
@else
    <div class="text-sm text-gray-500 dark:text-gray-400 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 text-center">
        Salve a tarefa primeiro para adicionar histórico e anotações.
    </div>
@endif
