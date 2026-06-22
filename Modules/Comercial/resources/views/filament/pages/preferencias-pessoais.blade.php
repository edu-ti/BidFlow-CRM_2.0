<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        corePlugins: { preflight: false },
      }
    </script>
    
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-8 max-w-5xl mx-auto">
        <!-- Main Header -->
        <h1 class="text-2xl text-gray-800 dark:text-gray-100 mb-6">Conta</h1>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-8 flex">
            <button class="text-[#3b82f6] border-b-2 border-[#3b82f6] pb-2 px-1 text-sm font-medium">Conta</button>
        </div>

        <!-- Section Geral -->
        <div class="mb-12">
            <h2 class="text-xl text-gray-800 dark:text-gray-100 mb-6">Geral</h2>

            <div class="space-y-6">
                <!-- Avatar row -->
                <div class="flex items-start">
                    <div class="w-1/4"></div>
                    <div class="w-3/4 flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center overflow-hidden">
                            <x-heroicon-s-user class="w-8 h-8 text-gray-400 dark:text-gray-500 mt-2"/>
                        </div>
                        <div>
                            <div class="text-sm">
                                <a href="#" class="text-[#3b82f6] hover:underline font-bold">Escolher foto</a>
                                <span class="text-gray-400 mx-1">&middot;</span>
                                <a href="#" class="text-[#3b82f6] hover:underline font-bold">Apagar</a>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Tamanho máx. 2MB. Formatos: JPG, GIF, PNG.</p>
                        </div>
                    </div>
                </div>

                <!-- Form: Seu nome -->
                <div class="flex items-center">
                    <label class="w-1/4 text-sm text-gray-700 dark:text-gray-300 text-right pr-6">Seu nome</label>
                    <div class="w-3/4 max-w-lg">
                        <input type="text" wire:model.defer="name" class="w-full rounded border-gray-300 dark:border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-800 py-1.5 px-3 border outline-none">
                    </div>
                </div>

                <!-- Form: E-mail -->
                <div class="flex items-center">
                    <label class="w-1/4 text-sm text-gray-700 dark:text-gray-300 text-right pr-6">E-mail</label>
                    <div class="w-3/4 max-w-lg flex items-center gap-2">
                        <input type="email" wire:model.defer="email" disabled class="w-full rounded border-gray-300 dark:border-gray-700 shadow-sm sm:text-sm bg-gray-100 dark:bg-gray-800 text-gray-500 py-1.5 px-3 border cursor-not-allowed">
                        <button type="button" class="border border-gray-300 dark:border-gray-600 rounded p-1.5 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <x-heroicon-o-pencil class="w-4 h-4 text-gray-600 dark:text-gray-400"/>
                        </button>
                    </div>
                </div>

                <!-- Submit Button Geral -->
                <div class="flex items-center mt-2">
                    <div class="w-1/4"></div>
                    <div class="w-3/4">
                        <button type="button" wire:click="salvarGeral" class="bg-[#7eb87d] hover:bg-[#6ca46b] text-white font-bold py-1.5 px-4 rounded text-sm transition-colors shadow-sm">
                            Alterar/Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-gray-800 my-8">

        <!-- Section Alterar Senha -->
        <div>
            <h2 class="text-xl text-gray-800 dark:text-gray-100 mb-4">Alterar senha</h2>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-8">
                Mantenha seus dados seguros criando uma senha complexa e longa. Pense em algo fácil de lembrar mas difícil de ser adivinhado por outras pessoas.
            </p>

            <div class="space-y-6">
                <!-- Form: Senha atual -->
                <div class="flex items-center">
                    <label class="w-1/4 text-sm text-gray-700 dark:text-gray-300 text-right pr-6">Senha atual</label>
                    <div class="w-3/4 max-w-sm">
                        <input type="password" wire:model.defer="current_password" class="w-full rounded border-gray-300 dark:border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-800 py-1.5 px-3 border outline-none">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Form: Nova senha -->
                <div class="flex items-center">
                    <label class="w-1/4 text-sm text-gray-700 dark:text-gray-300 text-right pr-6">Nova senha</label>
                    <div class="w-3/4 flex items-center gap-3">
                        <div class="w-full max-w-sm">
                            <input type="password" wire:model.defer="new_password" class="w-full rounded border-gray-300 dark:border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-800 py-1.5 px-3 border outline-none">
                        </div>
                        <span class="text-sm text-gray-500">mínimo de 8 caracteres</span>
                    </div>
                </div>
                @error('new_password')
                <div class="flex">
                    <div class="w-1/4"></div>
                    <div class="w-3/4"><span class="text-red-500 text-xs">{{ $message }}</span></div>
                </div>
                @enderror

                <!-- Form: Confirmar senha -->
                <div class="flex items-center">
                    <label class="w-1/4 text-sm text-gray-700 dark:text-gray-300 text-right pr-6">Confirmar senha</label>
                    <div class="w-3/4 max-w-sm">
                        <input type="password" wire:model.defer="new_password_confirmation" class="w-full rounded border-gray-300 dark:border-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-800 py-1.5 px-3 border outline-none">
                    </div>
                </div>

                <!-- Logout Checkbox -->
                <div class="flex items-start">
                    <div class="w-1/4"></div>
                    <div class="w-3/4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.defer="logout_other_devices" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Fazer logout de todos os outros dispositivos</span>
                            <x-heroicon-o-information-circle class="w-4 h-4 text-gray-500"/>
                        </label>
                    </div>
                </div>

                <!-- Submit Button Senha -->
                <div class="flex items-center mt-4">
                    <div class="w-1/4"></div>
                    <div class="w-3/4">
                        <button type="button" wire:click="alterarSenha" class="bg-[#7eb87d] hover:bg-[#6ca46b] text-white font-bold py-1.5 px-4 rounded text-sm transition-colors shadow-sm">
                            Alterar senha
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
