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
                    <x-filament::button wire:click="marcarComoGanho" color="success" size="sm" icon="heroicon-m-check">Ganho</x-filament::button>
                    <x-filament::button wire:click="marcarComoPerdido" color="danger" size="sm" icon="heroicon-m-x-mark">Perdido</x-filament::button>
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
                    <button wire:click="setTab('anotacoes')" class="pb-3 font-semibold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'anotacoes' ? 'border-b-2 border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-o-pencil-square class="w-4 h-4"/> Anotações
                    </button>
                    <button wire:click="setTab('atividade')" class="pb-3 font-semibold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'atividade' ? 'border-b-2 border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-o-calendar class="w-4 h-4"/> Atividade
                    </button>
                    <button wire:click="setTab('propostas')" class="pb-3 font-semibold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'propostas' ? 'border-b-2 border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-o-document-currency-dollar class="w-4 h-4"/> Propostas
                    </button>
                    <button wire:click="setTab('arquivos')" class="pb-3 font-semibold flex items-center gap-2 whitespace-nowrap {{ $activeTab === 'arquivos' ? 'border-b-2 border-primary-600 text-primary-600 dark:text-primary-400' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                        <x-heroicon-o-document-text class="w-4 h-4"/> Arquivos
                    </button>
                </div>

                <!-- Input area -->
                <div class="mb-8 p-1">
                    @if($activeTab === 'anotacoes')
                        <textarea wire:model="novaAnotacao" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 p-4 shadow-sm" rows="2" placeholder="Clique aqui para adicionar uma anotação..."></textarea>
                        <div class="flex justify-end mt-3">
                            <x-filament::button wire:click="salvarAnotacao" color="success">Salvar anotação</x-filament::button>
                        </div>
                    @elseif($activeTab === 'atividade')
                        <div class="flex flex-col gap-3">
                            <input wire:model="novaAtividadeTitulo" type="text" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 p-3 shadow-sm" placeholder="Título da Atividade (Ex: Ligar para o cliente)">
                            <div class="flex gap-3 items-center">
                                <input wire:model="novaAtividadeData" type="datetime-local" class="rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 p-3 shadow-sm">
                                <div class="flex-1 flex justify-end">
                                    <x-filament::button wire:click="salvarAtividade" color="success">Agendar Atividade</x-filament::button>
                                </div>
                            </div>
                        </div>
                    @elseif($activeTab === 'propostas')
                        <div class="flex flex-col gap-4">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 text-sm">Propostas Comerciais</h3>
                                <a href="{{ \Modules\Comercial\Filament\Resources\PropostaComercialResource::getUrl('create', ['oportunidade_id' => $record->id, 'fornecedor_id' => $record->fornecedor_id]) }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-500 text-white text-xs font-bold rounded-lg shadow transition-colors">
                                    + Nova Proposta
                                </a>
                            </div>
                            
                            @forelse($record->propostas as $proposta)
                                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex justify-between items-center hover:shadow-md transition-shadow">
                                    <div class="flex flex-col gap-1">
                                        <a href="{{ \Modules\Comercial\Filament\Resources\PropostaComercialResource::getUrl('edit', ['record' => $proposta->id]) }}" class="font-bold text-primary-600 hover:underline">
                                            Proposta {{ $proposta->numero ? '#'.$proposta->numero : '#'.str_pad($proposta->id, 4, '0', STR_PAD_LEFT) }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Data: {{ $proposta->data_proposta ? \Carbon\Carbon::parse($proposta->data_proposta)->format('d/m/Y') : \Carbon\Carbon::parse($proposta->created_at)->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="text-right flex flex-col items-end gap-1">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400 rounded text-[10px] font-bold uppercase">{{ $proposta->status }}</span>
                                        <p class="font-bold text-gray-900 dark:text-white">R$ {{ number_format($proposta->valor_total, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                    Nenhuma proposta gerada para esta oportunidade ainda.
                                </div>
                            @endforelse
                        </div>
                    @elseif($activeTab === 'arquivos')
                        <div class="flex flex-col items-center justify-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                            <x-heroicon-o-cloud-arrow-up class="w-12 h-12 text-gray-400 mb-2"/>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Módulo de arquivos em desenvolvimento.</p>
                        </div>
                    @endif
                </div>

                <!-- Histórico / Timeline (Left Aligned) -->
                <div>
                    <div class="flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-800 pb-2">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Histórico</h3>
                    </div>
                    
                    <div class="relative pl-6 border-l-2 border-gray-200 dark:border-gray-700 space-y-6 ml-2">
                        
                        @foreach($record->historicos()->latest()->get() as $historico)
                            <div class="relative">
                                <!-- Icon -->
                                <div class="absolute -left-[35px] flex items-center justify-center w-8 h-8 rounded-full border-4 border-white dark:border-gray-900 {{ $historico->tipo === 'sistema' ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400' : ($historico->tipo === 'email' ? 'bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-400' : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/50 dark:text-yellow-400') }}">
                                    @if($historico->tipo === 'sistema')
                                        <x-heroicon-s-bell-alert class="w-4 h-4"/>
                                    @elseif($historico->tipo === 'email')
                                        <x-heroicon-s-envelope class="w-4 h-4"/>
                                    @else
                                        <x-heroicon-s-pencil-square class="w-4 h-4"/>
                                    @endif
                                </div>
                                <!-- Card -->
                                @if($historico->tipo === 'sistema')
                                    <div class="py-1">
                                        <div class="flex items-center justify-between">
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">{{ $historico->nota }}</div>
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400 text-xs mt-1">
                                            {{ \Carbon\Carbon::parse($historico->created_at)->diffForHumans() }} - {{ \App\Models\User::find($historico->user_id)->name ?? 'Sistema' }}
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 dark:bg-yellow-900/10 rounded-lg p-4 border border-yellow-100 dark:border-yellow-900/30">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium text-gray-900 dark:text-white text-sm">
                                                <span class="font-bold">{{ \App\Models\User::find($historico->user_id)->name ?? 'Usuário' }}</span> adicionou uma anotação
                                            </div>
                                            <time class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($historico->created_at)->diffForHumans() }}</time>
                                        </div>
                                        <div class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line">
                                            {{ $historico->nota }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach

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
