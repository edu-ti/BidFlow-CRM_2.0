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
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div class="flex flex-wrap gap-2">
            @foreach($fabricantes as $fab_id => $fab_nome)
                <button wire:click="setFabricante('{{ $fab_id }}')" 
                        class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all shadow-sm {{ $fabricante_id == $fab_id ? 'bg-primary-600 text-white border border-primary-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    {{ $fab_nome }}
                </button>
            @endforeach
        </div>
        <div class="flex items-center space-x-3 bg-white dark:bg-gray-800 px-2 py-1.5 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <button wire:click="previousYear" class="px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-600 dark:text-gray-400 font-bold transition">
                &lt;
            </button>
            <span class="font-bold text-gray-900 dark:text-white px-2">{{ $year }}</span>
            <button wire:click="nextYear" class="px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded text-gray-600 dark:text-gray-400 font-bold transition">
                &gt;
            </button>
        </div>
    </div>

    <div class="flex gap-6 overflow-x-auto pb-4">
        @foreach($months as $num => $monthName)
            <div class="kanban-col flex flex-col bg-gray-50 dark:bg-gray-900 rounded-xl p-4 ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex justify-between items-center mb-4 px-1">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm tracking-wider">{{ $monthName }}</h3>
                    <span class="text-gray-900 dark:text-white text-sm font-bold bg-white dark:bg-gray-800 px-2 py-1 rounded shadow-sm border border-gray-100 dark:border-gray-700">
                        R$ {{ number_format(collect($itensMes[$num] ?? [])->sum('valor_total'), 2, ',', '.') }}
                    </span>
                </div>

                <div class="flex flex-col gap-3 min-h-[150px]">
                    @foreach($itensMes[$num] ?? [] as $item)
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <a href="{{ \Modules\Comercial\Filament\Resources\OportunidadeResource::getUrl('edit', ['record' => $item['proposta']['oportunidade_id']]) }}" class="font-semibold text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 truncate w-full text-sm">
                                    {{ $item['produto']['nome'] }}
                                </a>
                            </div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                <x-heroicon-o-building-office class="w-3 h-3"/>
                                <span class="truncate">{{ !empty($item['proposta']['fornecedor']['nome_fantasia']) ? $item['proposta']['fornecedor']['nome_fantasia'] : ($item['proposta']['fornecedor']['razao_social'] ?? 'Cliente') }}</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-500 flex items-center gap-1">
                                <x-heroicon-o-cube class="w-3 h-3"/>
                                <span>Qtd: {{ $item['quantidade'] }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <div class="text-xs font-bold text-success-600 dark:text-success-400">
                                    R$ {{ number_format($item['valor_total'], 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($itensMes[$num]))
                        <div class="flex-1 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center p-6 text-gray-400 dark:text-gray-500 text-sm">
                            Nenhuma venda.
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
