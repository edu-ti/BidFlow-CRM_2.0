<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        corePlugins: { preflight: false },
      }
    </script>
    
    <style>
        .pipedrive-tab { padding-bottom: 12px; margin-right: 24px; font-weight: 600; font-size: 14px; cursor: pointer; color: #475569; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .pipedrive-tab:hover { color: #1e293b; }
        .pipedrive-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
        .pipedrive-btn-green { background-color: #2b9d4e; color: white; font-weight: 700; border-radius: 4px; padding: 6px 16px; font-size: 13px; transition: background 0.2s; }
        .pipedrive-btn-green:hover { background-color: #238040; }
        .pipedrive-btn-white { background-color: white; border: 1px solid #cbd5e1; color: #334155; font-weight: 600; border-radius: 4px; padding: 6px 16px; font-size: 13px; transition: background 0.2s; }
        .pipedrive-btn-white:hover { background-color: #f8fafc; }
    </style>

    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 pb-8 min-h-[600px] relative">
        
        <!-- Header & Tabs -->
        <div class="pt-6 px-6 border-b border-gray-200 dark:border-gray-800">
            <h1 class="text-2xl text-gray-800 dark:text-gray-100 mb-6 font-semibold">Usuários e acesso</h1>
            
            <div class="flex items-center">
                <button wire:click="setTab('usuarios')" class="pipedrive-tab {{ $activeTab === 'usuarios' ? 'active' : '' }} flex items-center gap-2">
                    <x-heroicon-o-users class="w-4 h-4"/> Usuários e acesso
                </button>
                <button wire:click="setTab('permissoes')" class="pipedrive-tab {{ $activeTab === 'permissoes' ? 'active' : '' }} flex items-center gap-2">
                    <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4"/> Conjuntos de permissão
                </button>
                <button wire:click="setTab('visibilidade')" class="pipedrive-tab {{ $activeTab === 'visibilidade' ? 'active' : '' }} flex items-center gap-2">
                    <x-heroicon-o-squares-2x2 class="w-4 h-4"/> Grupos de visibilidade
                </button>
                <button wire:click="setTab('equipes')" class="pipedrive-tab {{ $activeTab === 'equipes' ? 'active' : '' }} flex items-center gap-2">
                    <x-heroicon-o-funnel class="w-4 h-4"/> Filtros de equipe
                </button>
            </div>
        </div>

        <!-- ABA 1: Usuários -->
        @if($activeTab === 'usuarios')
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">Defina o acesso dos usuários usando conjuntos de permissões (o que eles podem fazer) e grupos de visibilidade (o que eles podem ver). <a href="#" class="text-blue-600 hover:underline">Consulte o guia do sistema</a></p>
                <button class="pipedrive-btn-white flex items-center gap-1"><x-heroicon-o-academic-cap class="w-4 h-4"/> Saiba mais</button>
            </div>

            <div class="flex items-center justify-between mb-4">
                <div class="flex bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700 p-0.5">
                    <button class="px-4 py-1.5 text-sm font-semibold bg-white dark:bg-gray-700 shadow-sm rounded text-blue-600 border border-gray-200 dark:border-gray-600">Ativo (1)</button>
                    <button class="px-4 py-1.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">Convidado (0)</button>
                    <button class="px-4 py-1.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">Desativado (0)</button>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2 text-gray-400"/>
                        <input type="text" placeholder="Buscar usuário" class="pl-9 pr-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded text-sm w-48 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                    </div>
                    <button class="pipedrive-btn-white flex items-center gap-1"><x-heroicon-o-arrow-up-tray class="w-4 h-4"/> Exportar <x-heroicon-m-chevron-down class="w-3 h-3"/></button>
                    <button class="pipedrive-btn-green flex items-center gap-1"><x-heroicon-m-plus class="w-4 h-4"/> Usuário</button>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700 text-xs font-semibold uppercase text-gray-500">
                        <tr>
                            <th class="p-3 w-8"><input type="checkbox" class="rounded border-gray-300"></th>
                            <th class="p-3 w-1/4">Ativo (1)</th>
                            <th class="p-3">Último login</th>
                            <th class="p-3 text-center">Config. da conta</th>
                            <th class="p-3 text-center">Conjuntos de permissão</th>
                            <th class="p-3">Grupo de visibilidade</th>
                            <th class="p-3"></th>
                        </tr>
                        <!-- Subheader for sets -->
                        <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-700 text-[11px] font-semibold text-gray-500">
                            <th colspan="4"></th>
                            <th class="p-2 border-l border-r border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between px-4">
                                    <span class="flex items-center gap-1"><x-heroicon-s-currency-dollar class="w-3 h-3"/> Deals</span>
                                    <span class="flex items-center gap-1"><x-heroicon-s-globe-americas class="w-3 h-3"/> Global</span>
                                    <span class="flex items-center gap-1"><x-heroicon-s-clipboard-document-check class="w-3 h-3"/> Projects</span>
                                </div>
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer group" onclick="window.location.href='/admin/visao-geral-usuario'">
                            <td class="p-3"><input type="checkbox" class="rounded border-gray-300"></td>
                            <td class="p-3 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-600 text-xs">EC</div>
                                <div class="leading-tight">
                                    <p class="font-bold text-blue-600 group-hover:underline">Eduardo Cabral <span class="text-gray-400 font-normal">(você)</span></p>
                                    <p class="text-xs text-gray-500">edu_ti@outlook.com</p>
                                </div>
                            </td>
                            <td class="p-3 text-gray-800 dark:text-gray-300">há 35 minutos</td>
                            <td class="p-3 text-center"><x-heroicon-s-check-badge class="w-5 h-5 text-green-500 mx-auto"/></td>
                            <td class="p-3 border-l border-r border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between px-2 gap-2">
                                    <span class="text-[10px] font-bold text-green-700 bg-green-100 border border-green-300 px-2 py-0.5 rounded uppercase w-full text-center truncate">Administrador</span>
                                    <span class="text-[10px] font-bold text-green-700 bg-green-100 border border-green-300 px-2 py-0.5 rounded uppercase w-full text-center truncate">Administrador</span>
                                    <span class="text-[10px] font-bold text-green-700 bg-green-100 border border-green-300 px-2 py-0.5 rounded uppercase w-full text-center truncate">Administrador</span>
                                </div>
                            </td>
                            <td class="p-3 text-gray-800 dark:text-gray-300 flex items-center gap-2">
                                <x-heroicon-o-users class="w-4 h-4 text-gray-400"/> <span class="font-bold text-gray-500">2 |</span> Grupo padrão
                            </td>
                            <td class="p-3 text-right"><button class="text-gray-400 hover:text-gray-700"><x-heroicon-m-ellipsis-horizontal class="w-5 h-5"/></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- ABA 2: Conjuntos de Permissão -->
        @if($activeTab === 'permissoes')
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">Conjuntos de permissões definem o que os usuários podem ou não fazer agrupando permissões por função. <a href="#" class="text-blue-600 hover:underline">Confira a estrutura de permissões</a></p>
                <button class="pipedrive-btn-white flex items-center gap-1"><x-heroicon-o-academic-cap class="w-4 h-4"/> Saiba mais</button>
            </div>

            <div class="flex items-center justify-end mb-4 gap-4">
                <span class="text-sm text-gray-500 flex items-center gap-1">2/3 conjuntos personalizados em uso <x-heroicon-o-information-circle class="w-4 h-4"/></span>
                <button wire:click="$set('showPermissionModal', true)" class="pipedrive-btn-green flex items-center gap-1"><x-heroicon-m-plus class="w-4 h-4"/> Conjunto de permissões <x-heroicon-m-chevron-down class="w-3 h-3 ml-1"/></button>
            </div>

            <div class="mb-8">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-2">Negócios e recursos globais</h3>
                <div class="border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-800 shadow-sm">
                    <div class="bg-gray-50 dark:bg-gray-800/50 p-3 border-b border-gray-200 flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <x-heroicon-m-chevron-up class="w-4 h-4"/> <x-heroicon-o-currency-dollar class="w-4 h-4"/> Conjuntos de permissões do Negócios
                    </div>
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Administrador dos negócios</p>
                            <p class="text-xs text-gray-500 mt-1">Veja e edite todos os dados de vendas e gerencie configurações de negócios a nível de empresa.</p>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-bold">
                            <x-heroicon-o-user class="w-4 h-4"/> 1
                        </div>
                    </div>
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center hover:bg-gray-50 cursor-pointer">
                        <div>
                            <p class="font-bold text-gray-800 text-sm">Usuário comum de negócios</p>
                            <p class="text-xs text-gray-500 mt-1">O acesso aos dados de vendas e as ações disponíveis podem ser limitados. Este conjunto é padrão para novos usuários.</p>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-bold">
                            <span class="text-orange-500 flex items-center gap-1"><x-heroicon-s-exclamation-triangle class="w-4 h-4"/> 0</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

        <!-- ABA 3: Grupos de Visibilidade -->
        @if($activeTab === 'visibilidade')
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">Os grupos de visibilidade definem o acesso ao funil, ajudam você a gerenciar os dados que seus usuários podem ver. <a href="#" class="text-blue-600 hover:underline">Veja casos de uso comuns</a></p>
                <button class="pipedrive-btn-white flex items-center gap-1"><x-heroicon-o-academic-cap class="w-4 h-4"/> Saiba mais</button>
            </div>

            <div class="flex items-center justify-end mb-4 gap-4">
                <span class="text-sm text-gray-500 flex items-center gap-1">0/15 grupos personalizados em uso <x-heroicon-o-information-circle class="w-4 h-4"/></span>
                <button wire:click="$set('showVisibilityModal', true)" class="pipedrive-btn-green flex items-center gap-1"><x-heroicon-m-plus class="w-4 h-4"/> Grupo de visibilidade</button>
            </div>

            <div class="border border-gray-200 dark:border-gray-700 rounded bg-white dark:bg-gray-800 shadow-sm mb-12">
                <div class="p-4 flex justify-between items-center">
                    <p class="font-bold text-gray-800 text-sm">Grupo padrão</p>
                    <div class="flex items-center gap-4 text-sm text-gray-500 font-bold">
                        <x-heroicon-o-user class="w-4 h-4"/> 1
                        <button class="pipedrive-btn-white text-xs py-1">Visão geral</button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-block p-4 rounded-full bg-gray-50 mb-4">
                    <x-heroicon-o-squares-plus class="w-12 h-12 text-gray-300"/>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">Nenhum grupo de visibilidade personalizado foi adicionado ainda</h3>
                <p class="text-sm text-gray-500">Comece segmentando a visibilidade em suas equipes. <a href="#" wire:click.prevent="$set('showVisibilityModal', true)" class="text-blue-600 hover:underline">+ Adicionar grupo de visibilidade</a></p>
            </div>
        </div>
        @endif

        <!-- ABA 4: Filtros de Equipe -->
        @if($activeTab === 'equipes')
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">Utilize a funcionalidade de equipes para rastrear metas compartilhadas e facilitar a geração de relatórios.</p>
                <button wire:click="$set('showTeamModal', true)" class="pipedrive-btn-green flex items-center gap-1"><x-heroicon-m-plus class="w-4 h-4"/> Equipe</button>
            </div>
            
            <p class="text-sm text-gray-500 flex items-center gap-1 mb-12">0/15 equipes em uso <x-heroicon-o-information-circle class="w-4 h-4"/></p>

            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-block p-4 rounded-full bg-gray-50 mb-4">
                    <x-heroicon-o-user-group class="w-16 h-16 text-gray-300"/>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-2">Nenhuma equipe ainda</h3>
                <p class="text-sm text-gray-500">Crie uma equipe para simplificar seus relatórios e rastrear metas compartilhadas</p>
            </div>
        </div>
        @endif

    </div>

    <!-- MODALS -->

    <!-- Modal Permission -->
    @if($showPermissionModal)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-xl w-full flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-bold text-lg">Adicionar novo conjunto de permissão</h3>
                <button wire:click="$set('showPermissionModal', false)" class="text-gray-400 hover:text-gray-600"><x-heroicon-m-x-mark class="w-5 h-5"/></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nome do conjunto de permissões</label>
                    <input type="text" wire:model.defer="permName" class="w-full border-gray-300 rounded p-2 text-sm focus:border-blue-500 outline-none border">
                    <p class="text-right text-xs text-gray-400 mt-1">255 caracteres</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descrição (opcional)</label>
                    <textarea wire:model.defer="permDesc" rows="3" class="w-full border-gray-300 rounded p-2 text-sm focus:border-blue-500 outline-none border"></textarea>
                    <p class="text-right text-xs text-gray-400 mt-1">255 caracteres</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Produto</label>
                    <select wire:model.defer="permProduct" class="w-full border-gray-300 rounded p-2 text-sm border outline-none">
                        <option>Selecione o produto</option>
                        <option>Sales</option>
                        <option>Global</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2 rounded-b-lg">
                <button wire:click="$set('showPermissionModal', false)" class="pipedrive-btn-white">Cancelar</button>
                <button wire:click="savePermission" class="pipedrive-btn-green">Salvar</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Visibility -->
    @if($showVisibilityModal)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-xl w-full flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-bold text-lg">Adicionar grupo</h3>
                <button wire:click="$set('showVisibilityModal', false)" class="text-gray-400 hover:text-gray-600"><x-heroicon-m-x-mark class="w-5 h-5"/></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nome (obrigatório)</label>
                    <input type="text" wire:model.defer="visName" class="w-full border-gray-300 rounded p-2 text-sm border">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Descrição</label>
                    <textarea wire:model.defer="visDesc" rows="3" class="w-full border-gray-300 rounded p-2 text-sm border"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Grupo de visibilidade principal</label>
                    <select wire:model.defer="visParent" class="w-full border-gray-300 rounded p-2 text-sm border">
                        <option>Nenhum</option>
                        <option>Grupo padrão</option>
                    </select>
                </div>
            </div>
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2 rounded-b-lg">
                <button wire:click="$set('showVisibilityModal', false)" class="pipedrive-btn-white">Cancelar</button>
                <button wire:click="saveVisibility" class="pipedrive-btn-green">Salvar</button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Team -->
    @if($showTeamModal)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-bold text-lg">Adicionar uma nova equipe</h3>
                <button wire:click="$set('showTeamModal', false)" class="text-gray-400 hover:text-gray-600"><x-heroicon-m-x-mark class="w-5 h-5"/></button>
            </div>
            <div class="p-6 flex gap-6">
                <div class="w-1/2 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nome da equipe (obrigatório)</label>
                        <input type="text" wire:model.defer="teamName" placeholder="Nome da equipe" class="w-full border-gray-300 rounded p-2 text-sm border outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Gerente da equipe (obrigatório)</label>
                        <select wire:model.defer="teamManager" class="w-full border-gray-300 rounded p-2 text-sm border outline-none">
                            <option>Gerente da equipe</option>
                            <option>Eduardo Cabral</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Descrição da equipe</label>
                        <textarea wire:model.defer="teamDesc" rows="3" placeholder="Descrição da equipe" class="w-full border-gray-300 rounded p-2 text-sm border outline-none"></textarea>
                    </div>
                </div>
                <div class="w-1/2">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-gray-700">Membros da equipe</label>
                        <span class="text-sm font-bold text-gray-500"><x-heroicon-o-user class="w-4 h-4 inline"/> 0</span>
                    </div>
                    <div class="relative mb-4">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2.5 text-gray-400"/>
                        <input type="text" placeholder="Buscar usuário" class="pl-9 pr-3 py-2 w-full border border-gray-300 rounded text-sm outline-none">
                    </div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 hover:bg-gray-50 p-2 rounded cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        Eduardo Cabral
                    </label>
                </div>
            </div>
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2 rounded-b-lg">
                <button wire:click="$set('showTeamModal', false)" class="pipedrive-btn-white">Cancelar</button>
                <button wire:click="saveTeam" class="pipedrive-btn-green">Salvar</button>
            </div>
        </div>
    </div>
    @endif

</x-filament-panels::page>
