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
    <!-- TOP HEADER PIPEDRIVE STYLE -->
    <div class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                    <x-heroicon-o-briefcase class="w-6 h-6 text-gray-500"/>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $record->titulo }}</h1>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 mr-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($record->user->name ?? 'User') }}&background=random" class="w-8 h-8 rounded-full">
                    <div class="text-sm">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $record->user->name ?? 'Sem dono' }}</p>
                        <p class="text-xs text-gray-500">Proprietário</p>
                    </div>
                </div>

                @if($record->status !== 'Fechado / Aprovado' && $record->status !== 'Perdido / Recusado')
                    <x-filament::button color="success" size="sm" icon="heroicon-m-check">Ganho</x-filament::button>
                    <x-filament::button color="danger" size="sm" icon="heroicon-m-x-mark">Perdido</x-filament::button>
                @elseif($record->status === 'Fechado / Aprovado')
                    <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400 rounded-md font-bold text-sm flex items-center gap-1">
                        <x-heroicon-m-check class="w-4 h-4"/> GANHO
                    </span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400 rounded-md font-bold text-sm flex items-center gap-1">
                        <x-heroicon-m-x-mark class="w-4 h-4"/> PERDIDO
                    </span>
                @endif
            </div>
        </div>

        <!-- Pipeline Bar -->
        @php
            $stages = ['Prospectando', 'Proposta', 'Negociação', 'Fechado / Aprovado'];
            $currentStageIndex = array_search($record->status, $stages);
            if ($currentStageIndex === false) $currentStageIndex = count($stages); // Se for Perdido
        @endphp
        <div class="flex h-8 gap-1 w-full p-1 rounded-lg">
            @foreach($stages as $index => $stage)
                @php
                    $isPast = $index <= $currentStageIndex && $record->status !== 'Perdido / Recusado';
                    $isLost = $record->status === 'Perdido / Recusado' && $index === 0; // Se perdeu, fica vermelho
                    
                    if ($record->status === 'Perdido / Recusado') {
                        $bgClass = 'bg-red-500 text-white font-medium';
                    } elseif ($isPast && $index === $currentStageIndex) {
                        $bgClass = 'bg-green-600 text-white font-bold';
                    } elseif ($isPast) {
                        $bgClass = 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300 font-medium';
                    } else {
                        $bgClass = 'bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-medium';
                    }

                    if ($index === 0) {
                        $clipPath = 'polygon(0 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 0 100%)';
                    } elseif ($index === count($stages) - 1) {
                        $clipPath = 'polygon(0 0, 100% 0, 100% 100%, 0 100%, 10px 50%)';
                    } else {
                        $clipPath = 'polygon(0 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 0 100%, 10px 50%)';
                    }
                @endphp
                <div class="flex-1 flex items-center justify-center text-[10px] md:text-xs {{ $bgClass }} transition-colors duration-200 hover:opacity-90 uppercase tracking-wide" style="clip-path: {{ $clipPath }}">
                    {{ $stage === 'Fechado / Aprovado' ? 'Ganho' : $stage }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Barra Esquerda: Infolist -->
        <div class="col-span-1 flex flex-col gap-4">
            {{ $this->infolist }}
        </div>

        <!-- Área Central: Timeline e Abas -->
        <div class="col-span-1 md:col-span-2 flex flex-col gap-4">
            
            <div class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl p-6">
                <!-- Abas Mockadas (Atividade, Anotações, E-mail) -->
                <div class="flex space-x-6 mb-4 border-b border-gray-200 dark:border-gray-800 overflow-x-auto text-sm">
                    <button class="pb-3 border-b-2 border-primary-600 text-primary-600 dark:text-primary-400 font-semibold flex items-center gap-2 whitespace-nowrap">
                        <x-heroicon-o-pencil-square class="w-4 h-4"/> Anotações
                    </button>
                    <button class="pb-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium flex items-center gap-2 whitespace-nowrap">
                        <x-heroicon-o-calendar class="w-4 h-4"/> Atividade
                    </button>
                    <button class="pb-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium flex items-center gap-2 whitespace-nowrap">
                        <x-heroicon-o-envelope class="w-4 h-4"/> E-mail
                    </button>
                    <button class="pb-3 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 font-medium flex items-center gap-2 whitespace-nowrap">
                        <x-heroicon-o-document-text class="w-4 h-4"/> Arquivos
                    </button>
                </div>

                <!-- Input area -->
                <div class="mb-8 p-1">
                    <textarea class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 p-4 shadow-sm" rows="2" placeholder="Clique aqui para adicionar uma anotação..."></textarea>
                    <div class="flex justify-end mt-3">
                        <x-filament::button color="success">Salvar anotação</x-filament::button>
                    </div>
                </div>

                <!-- Histórico / Timeline (Left Aligned) -->
                <div>
                    <div class="flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-800 pb-2">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Histórico</h3>
                    </div>
                    
                    <div class="relative pl-6 border-l-2 border-gray-200 dark:border-gray-700 space-y-6 ml-2">
                        
                        <!-- Timeline Item Exemplo (Anotação Simulada) -->
                        <div class="relative">
                            <!-- Icon -->
                            <div class="absolute -left-[35px] flex items-center justify-center w-8 h-8 rounded-full border-4 border-white dark:border-gray-900 bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400">
                                <x-heroicon-s-pencil-square class="w-4 h-4"/>
                            </div>
                            <!-- Card -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/10 rounded-lg p-4 border border-yellow-100 dark:border-yellow-900/30">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-medium text-gray-900 dark:text-white text-sm">
                                        <span class="font-bold">{{ $record->user->name ?? 'Sistema' }}</span> adicionou uma anotação
                                    </div>
                                    <time class="text-xs text-gray-500">Agora mesmo</time>
                                </div>
                                <div class="text-gray-700 dark:text-gray-300 text-sm">
                                    [Amostra] Precisamos fazer uma oferta melhor para este contato. Ele já está conversando com a concorrência.
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Item Exemplo (Criação) -->
                        <div class="relative">
                            <!-- Icon -->
                            <div class="absolute -left-[35px] flex items-center justify-center w-8 h-8 rounded-full border-4 border-white dark:border-gray-900 bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                <x-heroicon-s-plus class="w-4 h-4"/>
                            </div>
                            <!-- Card -->
                            <div class="py-1">
                                <div class="flex items-center justify-between">
                                    <div class="font-medium text-gray-900 dark:text-white text-sm">Negócio Criado</div>
                                </div>
                                <div class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                    Hoje às {{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }} - {{ $record->user->name ?? 'Sistema' }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
