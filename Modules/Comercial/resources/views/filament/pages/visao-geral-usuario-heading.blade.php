<div x-data="{ open: false }" class="relative inline-flex items-center gap-2">
    <!-- Title text -->
    <h1 class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">Eduardo Cabral (você)</h1>
    
    <!-- Dropdown Trigger -->
    <button @click="open = !open" @click.away="open = false" class="p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
        <x-heroicon-m-chevron-down class="w-5 h-5 text-gray-500" />
    </button>
    
    <!-- Dropdown Content -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95" 
         x-cloak 
         class="absolute top-full left-0 mt-2 w-72 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl z-50 overflow-hidden">
        
        <!-- Search bar inside dropdown -->
        <div class="p-3 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2.5 text-gray-400" />
                <input type="text" placeholder="Buscar usuários" class="w-full pl-9 pr-3 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded text-sm outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 shadow-sm">
            </div>
        </div>
        
        <!-- User list -->
        <div class="max-h-60 overflow-y-auto p-2 space-y-1">
            <!-- Active User -->
            <button class="w-full text-left px-3 py-2 text-sm bg-blue-50/50 dark:bg-blue-900/20 hover:bg-blue-50 dark:hover:bg-blue-900/40 rounded flex items-center justify-between">
                <div>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">Eduardo Cabral</span>
                    <span class="text-gray-500 ml-1">(você)</span>
                </div>
                <x-heroicon-m-check class="w-4 h-4 text-blue-600 dark:text-blue-400" />
            </button>
            
            <!-- Other User Example -->
            <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 rounded flex items-center justify-between text-gray-700 dark:text-gray-300">
                <span>João Silva</span>
            </button>

            <!-- Other User Example -->
            <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800 rounded flex items-center justify-between text-gray-700 dark:text-gray-300">
                <span>Maria Oliveira</span>
            </button>
        </div>
    </div>
</div>
