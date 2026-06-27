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
        .kanban-col { min-width: 230px; }
    </style>
    <div class="flex gap-3 overflow-x-auto pb-4"
         x-data="{
             draggedId: null,
             updateStatus(id, newStatus) {
                 $wire.updateOportunidadeStatus(id, newStatus);
             }
         }">
        
        @foreach($stages as $stage)
            <div class="kanban-col flex flex-col bg-gray-50 dark:bg-gray-900 rounded-lg p-2 ring-1 ring-gray-950/5 dark:ring-white/10"
                 @dragover.prevent
                 @drop="updateStatus(draggedId, '{{ $stage }}')"
            >
                <div class="flex justify-between items-center mb-2 px-1">
                    <h3 class="font-bold text-gray-900 dark:text-white uppercase text-[11px] tracking-wider">{{ $stage }}</h3>
                    <span class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                        {{ collect($oportunidades)->where('status', $stage)->count() }}
                    </span>
                </div>
                
                <div class="text-[11px] font-bold text-gray-600 dark:text-gray-400 mb-2 px-1">
                    R$ {{ number_format(collect($oportunidades)->where('status', $stage)->sum('valor_estimado'), 2, ',', '.') }}
                </div>

                <div class="flex flex-col gap-1.5 min-h-[150px]">
                    @foreach(collect($oportunidades)->where('status', $stage) as $op)
                        @php
                            $fornecedor = !empty($op['fornecedor_id']) ? \Modules\Fornecedores\Models\Fornecedor::find($op['fornecedor_id']) : null;
                            $days = (int) \Carbon\Carbon::parse($op['created_at'])->timezone('America/Sao_Paulo')->startOfDay()->diffInDays(now()->timezone('America/Sao_Paulo')->startOfDay());
                            $counterColor = $days > 15 ? 'text-red-600 dark:text-red-400' : ($days > 7 ? 'text-warning-600 dark:text-warning-400' : 'text-gray-700 dark:text-gray-300');
                        @endphp
                        <div class="bg-white dark:bg-gray-800 p-2 rounded shadow-sm border-l-4 border-primary-500 cursor-grab hover:shadow-md transition-all flex flex-col gap-1"
                             draggable="true"
                             @dragstart="draggedId = {{ $op['id'] }}; $event.dataTransfer.effectAllowed = 'move';"
                        >
                            <div class="flex items-center justify-between">
                                <a href="{{ \Modules\Comercial\Filament\Resources\OportunidadeResource::getUrl('view', ['record' => $op['id']]) }}" class="font-bold text-left text-gray-800 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 truncate w-full text-xs block cursor-pointer leading-tight">
                                    {{ $op['titulo'] }}
                                </a>
                                <button type="button" wire:click="mountAction('editOportunidade', { record: {{ $op['id'] }} })" class="text-gray-400 hover:text-primary-600 ml-1 shrink-0" title="Edição Rápida">
                                    <x-heroicon-s-pencil class="w-3 h-3"/>
                                </button>
                            </div>
                            
                            @if($fornecedor)
                                <div class="text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide truncate mt-0.5">
                                    {{ $fornecedor->razao_social }}
                                </div>
                                <div class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <x-heroicon-s-user class="w-3 h-3 text-gray-400 shrink-0"/>
                                    <span class="truncate">{{ $fornecedor->contato_nome ?: 'Sem contato' }}</span>
                                </div>
                            @endif
                            
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-0.5">
                                <x-heroicon-s-calendar class="w-3 h-3 text-gray-400 shrink-0"/>
                                {{ \Carbon\Carbon::parse($op['created_at'])->timezone('America/Sao_Paulo')->format('d/m/Y') }}
                            </div>

                            <div class="text-[9px] text-gray-400 dark:text-gray-500 flex items-center gap-1 mt-0.5">
                                <x-heroicon-m-arrow-path class="w-3 h-3 text-gray-400 shrink-0"/>
                                At. {{ \Carbon\Carbon::parse($op['updated_at'])->timezone('America/Sao_Paulo')->format('d/m/y H:i') }}
                            </div>

                            @if(!empty($op['motivo_perda']) && $op['status'] === 'Recusado')
                                <div class="text-[9px] text-red-600 dark:text-red-400 mt-1 font-semibold flex items-start gap-1 p-1 bg-red-50 dark:bg-red-950/30 rounded border border-red-100 dark:border-red-900/50">
                                    <x-heroicon-m-x-circle class="w-3 h-3 shrink-0"/>
                                    <span class="leading-tight break-words line-clamp-2" title="{{ $op['motivo_perda'] }}">Motivo: {{ $op['motivo_perda'] }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between items-end mt-1.5 pt-1.5 border-t border-gray-50 dark:border-gray-700/50">
                                <div class="font-bold text-primary-600 dark:text-primary-400 text-[11px]">
                                    R$ {{ number_format($op['valor_estimado'], 2, ',', '.') }}
                                </div>
                                <div class="flex flex-col items-end gap-0.5">
                                    <div class="text-[10px] font-bold flex items-center gap-0.5 {{ $counterColor }}">
                                        <x-heroicon-o-clock class="w-3 h-3"/>
                                        {{ $days }} {{ $days == 1 ? 'dia' : 'dias' }}
                                    </div>
                                    <div class="text-[9px] text-gray-400 dark:text-gray-500 truncate max-w-[80px]">
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
