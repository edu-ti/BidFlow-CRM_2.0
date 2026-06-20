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
        .kanban-col { min-width: 320px; }
    </style>
    <div class="flex gap-6 overflow-x-auto pb-4"
         x-data="{
             draggedId: null,
             updateStatus(id, newStatus) {
                 $wire.updateOportunidadeStatus(id, newStatus);
             }
         }">
        
        @foreach($stages as $stage)
            <div class="kanban-col flex flex-col bg-gray-50 dark:bg-gray-900 rounded-xl p-4 ring-1 ring-gray-950/5 dark:ring-white/10"
                 @dragover.prevent
                 @drop="updateStatus(draggedId, '{{ $stage }}')"
            >
                <div class="flex justify-between items-center mb-4 px-1">
                    <h3 class="font-bold text-gray-900 dark:text-white uppercase text-sm tracking-wider">{{ $stage }}</h3>
                    <span class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-semibold px-2 py-1 rounded-full">
                        {{ collect($oportunidades)->where('status', $stage)->count() }}
                    </span>
                </div>
                
                <div class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4 px-1">
                    R$ {{ number_format(collect($oportunidades)->where('status', $stage)->sum('valor_estimado'), 2, ',', '.') }}
                </div>

                <div class="flex flex-col gap-3 min-h-[150px]">
                    @foreach(collect($oportunidades)->where('status', $stage) as $op)
                        @php
                            $fornecedor = !empty($op['fornecedor_id']) ? \Modules\Fornecedores\Models\Fornecedor::find($op['fornecedor_id']) : null;
                            $days = \Carbon\Carbon::parse($op['created_at'])->diffInDays(now());
                            $counterColor = $days > 15 ? 'text-red-600 dark:text-red-400' : ($days > 7 ? 'text-warning-600 dark:text-warning-400' : 'text-gray-700 dark:text-gray-300');
                        @endphp
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border-l-4 border-primary-500 cursor-grab hover:shadow-md transition-all flex flex-col gap-1.5"
                             draggable="true"
                             @dragstart="draggedId = {{ $op['id'] }}; $event.dataTransfer.effectAllowed = 'move';"
                        >
                            <button type="button" wire:click="mountAction('editOportunidade', { record: {{ $op['id'] }} })" class="font-bold text-left text-gray-800 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 truncate w-full text-sm">
                                {{ $op['titulo'] }}
                            </button>
                            
                            @if($fornecedor)
                                <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide truncate">
                                    {{ $fornecedor->razao_social }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <x-heroicon-s-user class="w-3.5 h-3.5 text-gray-400"/>
                                    <span class="truncate">{{ $fornecedor->contato_nome ?: 'Sem contato' }}</span>
                                </div>
                            @endif
                            
                            <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <x-heroicon-s-calendar class="w-3.5 h-3.5 text-gray-400"/>
                                {{ \Carbon\Carbon::parse($op['created_at'])->format('d/m/Y') }}
                            </div>

                            <div class="flex justify-between items-end mt-2 pt-2 border-t border-gray-50 dark:border-gray-700/50">
                                <div class="font-bold text-primary-600 dark:text-primary-400 text-sm">
                                    R$ {{ number_format($op['valor_estimado'], 2, ',', '.') }}
                                </div>
                                <div class="flex flex-col items-end gap-0.5">
                                    <div class="text-xs font-bold flex items-center gap-1 {{ $counterColor }}">
                                        <x-heroicon-o-clock class="w-3.5 h-3.5"/>
                                        {{ $days }} {{ $days == 1 ? 'dia' : 'dias' }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 truncate max-w-[100px]">
                                        {{ auth()->user()->name ?? 'Usuário' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(collect($oportunidades)->where('status', $stage)->isEmpty())
                        <div class="flex-1 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center p-6 text-gray-400 dark:text-gray-500 text-sm">
                            Solte aqui
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
