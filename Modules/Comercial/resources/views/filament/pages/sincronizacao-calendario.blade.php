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
            }
          }
        }
      }
    </script>
    <div class="max-w-4xl space-y-6">
        
        <a href="/admin/agenda-semana-boards" class="flex items-center text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white font-medium mb-4">
            <x-heroicon-m-arrow-left class="w-4 h-4 mr-1"/> Voltar para Atividades
        </a>

        <!-- Sync Account Box -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-2">Sincronizando de</p>
                <div class="flex items-center gap-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/df/Microsoft_Office_Outlook_%282018%E2%80%93present%29.svg/2048px-Microsoft_Office_Outlook_%282018%E2%80%93present%29.svg.png" class="w-6 h-6"/>
                    <span class="font-bold text-gray-900 dark:text-white text-lg">{{ auth()->user()->email }}</span>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase mb-2 text-center">Status</p>
                    @if($syncStatus)
                        <span class="flex items-center gap-1 border border-green-300 bg-green-50 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                            <x-heroicon-o-check-circle class="w-4 h-4"/> Conectado
                        </span>
                    @else
                        <span class="flex items-center gap-1 border border-gray-300 bg-gray-50 text-gray-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                            Inativo
                        </span>
                    @endif
                </div>
                
                <div class="pt-6">
                    @if($syncStatus)
                        <button wire:click="toggleSync" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow-sm transition">
                            Parar sincronização
                        </button>
                    @else
                        <button wire:click="toggleSync" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-sm transition">
                            Iniciar sincronização
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-800 my-6">

        <!-- Calendar Selection -->
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-1/3">
                <h3 class="text-gray-900 dark:text-white font-bold flex items-center gap-1">
                    <x-heroicon-o-chevron-up class="w-4 h-4"/> Calendário <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400"/>
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selecione o calendário que deseja sincronizar.</p>
            </div>
            <div class="w-2/3">
                <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Selecionar calendário:</label>
                <select class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-800 p-2 border">
                    <option>Calendário Principal</option>
                    <option>Trabalho</option>
                </select>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-800 my-6">

        <!-- Sync Type -->
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-1/3">
                <h3 class="text-gray-900 dark:text-white font-bold flex items-center gap-1">
                    <x-heroicon-o-chevron-up class="w-4 h-4"/> Tipo de sincronização <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400"/>
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Escolha como seu calendário externo é sincronizado.</p>
            </div>
            <div class="w-2/3 grid grid-cols-2 gap-4">
                <button wire:click="$set('syncType', 'bidirectional')" class="border rounded-lg p-4 text-left transition-all {{ $syncType === 'bidirectional' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10 ring-1 ring-primary-500' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                    <div class="flex justify-center items-center gap-2 mb-2 text-primary-600">
                        <x-heroicon-s-cloud class="w-5 h-5"/>
                        <x-heroicon-s-arrows-right-left class="w-4 h-4"/>
                        <x-heroicon-s-calendar class="w-5 h-5"/>
                    </div>
                    <h4 class="font-bold text-center text-sm mb-2 text-gray-900 dark:text-white">Sincronização bidirecional</h4>
                    <ul class="text-xs text-gray-500 dark:text-gray-400 list-disc pl-4 space-y-1">
                        <li>As atividades são sincronizadas com seu calendário externo.</li>
                        <li>Os eventos do calendário externo são sincronizados como atividades e ficam visíveis.</li>
                    </ul>
                </button>
                <button wire:click="$set('syncType', 'unidirectional')" class="border rounded-lg p-4 text-left transition-all {{ $syncType === 'unidirectional' ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10 ring-1 ring-primary-500' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                    <div class="flex justify-center items-center gap-2 mb-2 text-green-600">
                        <x-heroicon-s-cloud class="w-5 h-5"/>
                        <x-heroicon-s-arrow-right class="w-4 h-4"/>
                        <x-heroicon-s-calendar class="w-5 h-5"/>
                    </div>
                    <h4 class="font-bold text-center text-sm mb-2 text-gray-900 dark:text-white">Sincronização unidirecional</h4>
                    <ul class="text-xs text-gray-500 dark:text-gray-400 list-disc pl-4 space-y-1">
                        <li>As atividades são sincronizadas com seu calendário externo.</li>
                        <li>Apenas as edições feitas nas atividades são sincronizadas.</li>
                    </ul>
                </button>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-800 my-6">

        <!-- Conversion Settings -->
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-1/3">
                <h3 class="text-gray-900 dark:text-white font-bold flex items-center gap-1">
                    <x-heroicon-o-chevron-up class="w-4 h-4"/> Convertendo eventos do calendário externo <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400"/>
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Especifique como os eventos do seu calendário externo aparecem no sistema.</p>
            </div>
            <div class="w-2/3">
                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 block mb-1">Converta os eventos de calendário em atividades como:</label>
                <select wire:model="conversionType" class="block w-full rounded-md border-gray-300 dark:border-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-gray-800 p-2 border mb-4">
                    <option>Chamada</option>
                    <option>Reunião</option>
                    <option>Tarefa</option>
                </select>

                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 block mb-2">Selecione as atividades que você deseja ver no seu calendário externo:</label>
                <div class="space-y-2">
                    @foreach(['Chamada', 'Reunião', 'Tarefa', 'Prazo', 'E-mail', 'Almoço'] as $type)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="activitiesToSync" value="{{ $type }}" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $type }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-800 my-6">

        <!-- Advanced settings -->
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-1/3">
                <h3 class="text-gray-900 dark:text-white font-bold flex items-center gap-1">
                    <x-heroicon-o-chevron-up class="w-4 h-4"/> Configurações avançadas <x-heroicon-o-information-circle class="w-4 h-4 text-gray-400"/>
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Escolha como os contatos vinculados são sincronizados com seus eventos.</p>
            </div>
            <div class="w-2/3 space-y-3">
                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 block mb-2">Sincronize informações sobre contatos e negócios:</label>
                <label class="flex items-start">
                    <input type="radio" wire:model="advancedSync" value="no_contact" class="mt-0.5 border-gray-300 text-primary-600 focus:ring-primary-500">
                    <div class="ml-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300 block">Não incluir informações de contato e de negócios</span>
                        <span class="text-xs text-gray-500 border border-gray-200 rounded px-1 uppercase font-bold">Recomendado</span>
                    </div>
                </label>
                <label class="flex items-center">
                    <input type="radio" wire:model="advancedSync" value="with_contact" class="border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Incluir informações de contato e negócio na descrição</span>
                </label>
            </div>
        </div>

        <div class="flex justify-center mt-12 pb-12">
             @if($syncStatus)
                <button wire:click="toggleSync" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-8 rounded shadow-sm transition">
                    Parar sincronização
                </button>
            @endif
        </div>
    </div>
</x-filament-panels::page>
