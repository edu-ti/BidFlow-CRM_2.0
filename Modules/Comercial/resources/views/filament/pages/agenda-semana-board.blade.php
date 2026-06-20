<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        corePlugins: { preflight: false },
        theme: {
          extend: {
            colors: {
              primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554' },
              success: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d', 950: '#052e16' },
            }
          }
        }
      }
    </script>
    <style>
        .kanban-col { min-width: 300px; }
    </style>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Atividades da Semana</h2>
        <div class="flex space-x-2 items-center">
            <button wire:click="previousWeek" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                &lt; Semana Anterior
            </button>
            <span class="font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 px-4 py-1.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                {{ $weekStart->format('d M') }} - {{ $weekStart->copy()->addDays(6)->format('d M') }}
            </span>
            <button wire:click="nextWeek" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                Próxima Semana &gt;
            </button>
            <button wire:click="currentWeek" class="ml-2 bg-primary-600 text-white px-3 py-1.5 rounded-lg shadow-sm hover:bg-primary-700 transition text-sm font-medium">
                Semana Atual
            </button>
        </div>
    </div>

    <div class="flex gap-6 overflow-x-auto pb-4"
         x-data="{
             draggedId: null,
             updateDate(id, newDate) {
                 $wire.moveTask(id, newDate);
             }
         }">
        
        @foreach($days as $day)
            <div class="kanban-col flex flex-col bg-gray-50 dark:bg-gray-900 rounded-xl p-4 ring-1 ring-gray-950/5 dark:ring-white/10"
                 @dragover.prevent
                 @drop="updateDate(draggedId, '{{ $day['date'] }}')"
            >
                <div class="flex justify-between items-center mb-4 px-1">
                    <h3 class="font-bold text-gray-900 dark:text-white uppercase text-sm tracking-wider">{{ $day['label'] }}</h3>
                    <span class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">
                        {{ count($day['tarefas']) }}
                    </span>
                </div>

                <div class="flex flex-col gap-3 min-h-[150px]">
                    @foreach($day['tarefas'] as $tarefa)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 cursor-grab hover:ring-primary-500 transition-all flex flex-col gap-2"
                             draggable="true"
                             @dragstart="draggedId = {{ $tarefa['id'] }}; $event.dataTransfer.effectAllowed = 'move';"
                        >
                            <div class="flex justify-between items-start">
                                <button type="button" wire:click="mountAction('editTarefa', { record: {{ $tarefa['id'] }} })" class="font-semibold text-left text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 truncate w-full">
                                    {{ $tarefa['titulo'] }}
                                </button>
                            </div>
                            @if(!empty($tarefa['fornecedor_id']))
                                <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                    <x-heroicon-o-building-office class="w-3 h-3"/>
                                    <span class="truncate">{{ \Modules\Fornecedores\Models\Fornecedor::find($tarefa['fornecedor_id'])?->razao_social }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <x-heroicon-o-clock class="w-3 h-3"/>
                                    {{ \Carbon\Carbon::parse($tarefa['data_inicio'])->format('H:i') }}
                                </div>
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $tarefa['status'] == 'Concluída' ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $tarefa['status'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                    
                    @if(count($day['tarefas']) === 0)
                        <div class="flex-1 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center p-6 text-gray-400 dark:text-gray-500 text-sm">
                            Solte aqui
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
