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

    <div class="flex flex-col gap-4">
        <!-- Toolbar (Filters, Toggle, Sync button) -->
        <div class="flex flex-col md:flex-row justify-between md:items-center bg-white dark:bg-gray-900 p-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 gap-4">
            <div class="flex items-center gap-3 overflow-x-auto">
                <!-- Toggle Group -->
                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-lg p-1 shrink-0">
                    <button wire:click="setViewMode('list')" class="px-3 py-1 rounded transition-colors {{ $viewMode === 'list' ? 'bg-white shadow text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-s-bars-4 class="w-4 h-4"/>
                    </button>
                    <button wire:click="setViewMode('calendar')" class="px-3 py-1 rounded transition-colors {{ $viewMode === 'calendar' ? 'bg-white shadow text-gray-900 dark:bg-gray-700 dark:text-white' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-s-calendar class="w-4 h-4"/>
                    </button>
                </div>
                
                <div class="shrink-0 hidden md:block">
                    <div class="h-6 border-r border-gray-300 dark:border-gray-700"></div>
                </div>
                
                <!-- Filters -->
                <div class="flex space-x-4 text-sm shrink-0 items-center h-full">
                    <button wire:click="setFilter('Tudo')" class="h-full flex items-center {{ $activityFilter === 'Tudo' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">Tudo</button>
                    <button wire:click="setFilter('Chamada')" class="h-full flex items-center gap-1 {{ $activityFilter === 'Chamada' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">Chamada</button>
                    <button wire:click="setFilter('Reunião')" class="h-full flex items-center gap-1 {{ $activityFilter === 'Reunião' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">Reunião</button>
                    <button wire:click="setFilter('Tarefa')" class="h-full flex items-center gap-1 {{ $activityFilter === 'Tarefa' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">Tarefa</button>
                    <button wire:click="setFilter('Prazo')" class="h-full flex items-center gap-1 {{ $activityFilter === 'Prazo' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">Prazo</button>
                    <button wire:click="setFilter('E-mail')" class="h-full flex items-center gap-1 {{ $activityFilter === 'E-mail' ? 'font-bold border-b-2 border-primary-600 text-gray-900 dark:text-white pt-[2px]' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">E-mail</button>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <span class="font-semibold text-sm border border-gray-200 dark:border-gray-700 px-3 py-1 rounded-md">{{ $weekStart->format('M d') }} - {{ $weekStart->copy()->addDays(6)->format('d, Y') }}</span>
                <div class="flex items-center bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md">
                    <button wire:click="previousWeek" class="px-2 py-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-md border-r border-gray-200 dark:border-gray-700"><x-heroicon-m-chevron-left class="w-4 h-4"/></button>
                    <button wire:click="currentWeek" class="px-3 py-1 text-sm font-bold hover:bg-gray-100 dark:hover:bg-gray-700">Hoje</button>
                    <button wire:click="nextWeek" class="px-2 py-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-md border-l border-gray-200 dark:border-gray-700"><x-heroicon-m-chevron-right class="w-4 h-4"/></button>
                </div>
                <a href="/admin/sincronizacao-calendario" class="px-3 py-1 text-[10px] font-bold border border-red-300 text-red-600 bg-red-50 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400 rounded-full hover:bg-red-100 transition whitespace-nowrap">
                    SINCRONIZAÇÃO INATIVA
                </a>
            </div>
        </div>

        @if($viewMode === 'list')
            <div class="w-full">
                {{ $this->table }}
            </div>
        @else
            <!-- Custom Calendar Grid -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm overflow-x-auto border border-gray-200 dark:border-gray-800">
                <div class="min-w-[800px]">
                    <!-- Header: Days -->
                    <div class="grid grid-cols-[60px_repeat(7,1fr)] border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 text-center text-xs font-bold py-2">
                        <div class="border-r border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500">JUN</div>
                        @foreach($days as $day)
                            <div class="border-r border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center gap-1 text-gray-600 dark:text-gray-300">
                                <span class="uppercase text-[10px]">{{ explode(' ', $day['label'])[0] }}</span>
                                <span class="text-sm font-bold {{ now()->format('Y-m-d') === $day['date'] ? 'bg-primary-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : '' }}">{{ explode(' ', $day['label'])[1] }}</span>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Time Grid -->
                    <div class="relative h-[800px] overflow-y-auto bg-white dark:bg-gray-900" style="scroll-behavior: smooth;">
                        <!-- Lines -->
                        @for($h=0; $h<24; $h++)
                            <div class="grid grid-cols-[60px_repeat(7,1fr)] border-b border-gray-100 dark:border-gray-800 h-[60px]">
                                <div class="text-[10px] font-medium text-gray-400 text-center pt-1 border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                    {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                                </div>
                                @for($d=0; $d<7; $d++)
                                    <div class="border-r border-gray-100 dark:border-gray-800 {{ $d > 4 ? 'bg-gray-50/50 dark:bg-gray-800/30' : '' }}"></div>
                                @endfor
                            </div>
                        @endfor

                        <!-- Events Overlay -->
                        <div class="absolute top-0 left-[60px] right-0 bottom-0 grid grid-cols-7 pointer-events-none">
                            @foreach($days as $index => $day)
                                <div class="relative">
                                    @foreach($day['tarefas'] as $tarefa)
                                        @php
                                            $hour = \Carbon\Carbon::parse($tarefa['data_inicio'])->format('H');
                                            $minute = \Carbon\Carbon::parse($tarefa['data_inicio'])->format('i');
                                            $top = ($hour * 60) + ($minute);
                                        @endphp
                                        <div class="absolute w-[95%] mx-auto left-0 right-0 bg-blue-100 dark:bg-blue-900/40 text-gray-800 dark:text-gray-200 border border-blue-200 dark:border-blue-800/50 rounded-md p-1.5 text-[10px] shadow-sm hover:ring-2 ring-blue-400 cursor-pointer overflow-hidden z-10 pointer-events-auto transition-shadow group"
                                             style="top: {{ $top }}px; height: 50px;"
                                             wire:click="mountAction('editTarefa', { record: {{ $tarefa['id'] }} })"
                                        >
                                            <div class="flex items-start gap-1">
                                                <x-heroicon-s-user class="w-3 h-3 text-gray-400 mt-0.5 shrink-0"/>
                                                <div class="flex flex-col overflow-hidden">
                                                    <span class="font-bold truncate {{ $tarefa['status'] == 'Concluída' ? 'line-through text-gray-500' : '' }}">[{{ $tarefa['oportunidade']['titulo'] ?? 'Negócio' }}] {{ $tarefa['titulo'] }}</span>
                                                </div>
                                                <div class="ml-auto shrink-0 w-3 h-3 rounded-full border-2 border-gray-300 {{ $tarefa['status'] == 'Concluída' ? 'bg-green-500 border-green-500' : 'bg-white' }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
