<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        corePlugins: { preflight: false },
      }
    </script>
    
    <div class="flex justify-end mb-4">
        <a href="/admin/preferencias-pessoais" class="bg-white border border-gray-300 text-gray-700 font-bold py-1.5 px-4 rounded text-sm hover:bg-gray-50 transition-colors shadow-sm dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">
            Editar perfil
        </a>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded shadow-sm border border-gray-200 dark:border-gray-800 flex min-h-[600px]">
        
        <!-- Sidebar Esquerda -->
        <div class="w-1/3 border-r border-gray-200 dark:border-gray-800 p-8 flex flex-col gap-6 bg-gray-50/50 dark:bg-gray-900/50">
            
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span class="text-right w-1/3 pr-4 text-gray-500">Nome</span>
                <span class="w-2/3 font-medium">Eduardo Cabral</span>
            </div>
            
            <div class="flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                <span class="text-right w-1/3 pr-4 text-gray-500">E-mail</span>
                <a href="mailto:edu_ti@outlook.com" class="w-2/3 text-blue-600 hover:underline font-medium">edu_ti@outlook.com</a>
            </div>

            <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300">
                <span class="text-right w-1/3 pr-4 text-gray-500">Último login</span>
                <span class="w-2/3">22 de junho de 2026 às 11:34</span>
            </div>

        </div>

        <!-- Área Principal (Timeline) -->
        <div class="w-2/3">
            
            <!-- Tab Header -->
            <div class="border-b border-gray-200 dark:border-gray-800 px-8 pt-4">
                <button class="text-[#3b82f6] border-b-2 border-[#3b82f6] pb-3 px-1 text-sm font-bold">Atualizações</button>
            </div>

            <div class="p-8">
                <!-- Timeline Container -->
                <div class="relative border-l border-gray-200 dark:border-gray-700 ml-6 space-y-10 pb-8">
                    
                    <!-- Item 1: Atividade -->
                    <div class="relative pl-12">
                        <!-- Icon -->
                        <div class="absolute -left-4 top-1 w-8 h-8 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                            <x-heroicon-o-calendar class="w-4 h-4 text-gray-500"/>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <x-heroicon-s-user class="w-4 h-4"/>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    <span class="font-bold">Eduardo Cabral</span> adicionou uma nova atividade: 
                                    <a href="#" class="text-blue-600 font-bold hover:underline">[Sample] Apresente o Projects a outros usuários da empresa</a>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">20 de junho de 2026 às 15:39 &middot; Eduardo Cabral</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2: Atualizou Negócio -->
                    <div class="relative pl-12">
                        <div class="absolute -left-4 top-1 w-8 h-8 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                            <x-heroicon-o-currency-dollar class="w-4 h-4 text-gray-500"/>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <x-heroicon-s-user class="w-4 h-4"/>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    <span class="font-bold">Eduardo Cabral</span> atualizou o negócio: 
                                    <a href="#" class="text-blue-600 font-bold hover:underline">[Amostra] Tony Turner</a>
                                </p>
                                <div class="bg-gray-50 dark:bg-gray-800 rounded p-2 mt-2 border border-gray-100 dark:border-gray-700 inline-block">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Etapa: <span class="line-through text-gray-400">Demo agendada</span> <x-heroicon-m-chevron-right class="w-3 h-3 inline"/> <span class="font-bold">Qualificado</span></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">20 de junho de 2026 às 15:38 &middot; Eduardo Cabral</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3: Atualizou Negócio (reverso) -->
                    <div class="relative pl-12">
                        <div class="absolute -left-4 top-1 w-8 h-8 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                            <x-heroicon-o-currency-dollar class="w-4 h-4 text-gray-500"/>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <x-heroicon-s-user class="w-4 h-4"/>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    <span class="font-bold">Eduardo Cabral</span> atualizou o negócio: 
                                    <a href="#" class="text-blue-600 font-bold hover:underline">[Amostra] Tony Turner</a>
                                </p>
                                <div class="bg-gray-50 dark:bg-gray-800 rounded p-2 mt-2 border border-gray-100 dark:border-gray-700 inline-block">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Etapa: <span class="line-through text-gray-400">Qualificado</span> <x-heroicon-m-chevron-right class="w-3 h-3 inline"/> <span class="font-bold">Demo agendada</span></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">20 de junho de 2026 às 15:38 &middot; Eduardo Cabral</p>
                            </div>
                        </div>
                    </div>

                    <!-- Item 4: Demo concluída -->
                    <div class="relative pl-12">
                        <div class="absolute -left-4 top-1 w-8 h-8 rounded-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                            <x-heroicon-o-currency-dollar class="w-4 h-4 text-gray-500"/>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <x-heroicon-s-user class="w-4 h-4"/>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800 dark:text-gray-200">
                                    <span class="font-bold">Eduardo Cabral</span> atualizou o negócio: 
                                    <a href="#" class="text-blue-600 font-bold hover:underline">[Amostra] Tony Turner</a>
                                </p>
                                <div class="bg-gray-50 dark:bg-gray-800 rounded p-2 mt-2 border border-gray-100 dark:border-gray-700 inline-block">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Etapa: <span class="line-through text-gray-400">Demo concluída</span> <x-heroicon-m-chevron-right class="w-3 h-3 inline"/> <span class="font-bold">Demo agendada</span></p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">20 de junho de 2026 às 15:32 &middot; Eduardo Cabral</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>
