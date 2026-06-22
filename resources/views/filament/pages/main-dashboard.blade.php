<x-filament-panels::page>
    <!-- Include ApexCharts via CDN -->
    <!-- Include Tailwind CSS via CDN with Preflight Disabled to avoid breaking Filament -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        corePlugins: { preflight: false },
        darkMode: 'class'
      }
    </script>

    <div x-data="dashboardData()" class="flex flex-col gap-6" x-cloak>
        
        <!-- Header & Tabs Area -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Bem-vindo(a) de volta, Eduardo!
                </h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}
                </p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Abas (Tabs) -->
                <div class="flex bg-white dark:bg-gray-900 rounded-lg p-1 shadow-sm border border-gray-200 dark:border-gray-800">
                    <button 
                        @click="setTab('comercial')" 
                        :class="activeTab === 'comercial' ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="px-4 py-2 rounded-md text-sm transition-all duration-200"
                    >
                        Comercial
                    </button>
                    <button 
                        @click="setTab('licitacao')" 
                        :class="activeTab === 'licitacao' ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="px-4 py-2 rounded-md text-sm transition-all duration-200"
                    >
                        Licitação
                    </button>
                </div>

                <!-- Filtro & Exportar -->
                <x-filament::button color="gray" icon="heroicon-m-calendar" outlined>
                    Este Mês
                </x-filament::button>
                
                <x-filament::button color="gray" icon="heroicon-m-arrow-down-tray" class="bg-gray-900 text-white hover:bg-gray-800 dark:bg-white dark:text-gray-900">
                    Exportar
                </x-filament::button>
            </div>
        </div>

        <!-- 1st Row: Custom Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Taxa de Conversão -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Taxa de Conversão</h3>
                    <x-heroicon-m-chevron-right class="text-gray-400" style="width: 20px; height: 20px;"/>
                </div>
                <div class="flex items-end justify-between mb-4">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-gray-900 dark:text-white" x-text="data.conversionRate"></span>
                        <span class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                            <x-heroicon-m-arrow-trending-up style="width: 16px; height: 16px;"/>
                            12%
                        </span>
                    </div>
                </div>
                <div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mb-2">
                        <div class="bg-emerald-500 h-2 rounded-full" :style="'width: ' + data.conversionRate"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Quase todas as metas atingidas.</p>
                </div>
            </div>

            <!-- Receita de Vendas -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Receita de Vendas</h3>
                    <x-heroicon-m-chevron-right class="text-gray-400" style="width: 20px; height: 20px;"/>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <div class="flex items-baseline gap-1">
                            <span class="text-lg text-gray-500">R$</span>
                            <span class="text-4xl font-bold text-gray-900 dark:text-white" x-text="data.revenue"></span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Sua receita aumentou este mês em <span class="text-emerald-500 font-semibold">R$ 4.210</span>
                        </p>
                    </div>
                    <!-- Mini Bar Chart placeholder -->
                    <div class="flex items-end gap-1 h-12">
                        <div class="w-3 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 40%"></div>
                        <div class="w-3 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 60%"></div>
                        <div class="w-3 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 50%"></div>
                        <div class="w-3 bg-blue-500 rounded-sm" style="height: 90%"></div>
                    </div>
                </div>
            </div>

            <!-- Taxa de Retenção/Aprovação -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Taxa de Sucesso</h3>
                    <x-heroicon-m-chevron-right class="text-gray-400" style="width: 20px; height: 20px;"/>
                </div>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> 75.3%
                                <span class="text-emerald-600 font-normal text-xs flex items-center"><x-heroicon-m-arrow-trending-up style="width: 12px; height: 12px;"/> 2.424</span>
                            </span>
                            <span class="text-gray-500">12.565 Clientes</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 75.3%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-amber-500"></div> 24.7%
                                <span class="text-red-500 font-normal text-xs flex items-center"><x-heroicon-m-arrow-trending-down style="width: 12px; height: 12px;"/> 213</span>
                            </span>
                            <span class="text-gray-500">1.421 Propostas</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 24.7%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2nd Row: Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <template x-for="(stat, index) in data.stats" :key="index">
                <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                            <span x-html="stat.icon"></span>
                        </div>
                        <h4 class="text-gray-700 dark:text-gray-300 font-medium" x-text="stat.label"></h4>
                        <x-heroicon-m-information-circle class="text-gray-400 ml-auto" style="width: 16px; height: 16px;"/>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2" x-text="stat.value"></div>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 font-medium flex items-center gap-1">
                            <x-heroicon-m-arrow-up style="width: 12px; height: 12px;"/>
                            <span x-text="stat.growth"></span>
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">Mês passado</span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Insights (Bar Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Receita Mensal</h3>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">R$ 145.567,00</span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-sm font-medium flex items-center gap-1"><x-heroicon-m-arrow-up style="width: 12px; height: 12px;"/> 4.9%</span>
                        </div>
                    </div>
                    <div class="flex bg-gray-100 dark:bg-gray-800 rounded-full p-1">
                        <button class="px-3 py-1 rounded-full text-sm font-medium bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm">Mensal</button>
                        <button class="px-3 py-1 rounded-full text-sm font-medium text-gray-500 hover:text-gray-900 dark:hover:text-white">Anual</button>
                    </div>
                </div>
                <div id="revenueChart" class="w-full h-64"></div>
            </div>

            <!-- Sales Overview (Gauge) -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Metas de Vendas</h3>
                    <x-heroicon-m-ellipsis-horizontal class="text-gray-400" style="width: 24px; height: 24px;"/>
                </div>
                <div id="gaugeChart" class="w-full flex-grow flex items-center justify-center -mt-8"></div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-500 font-medium">Atual</span>
                        <span class="text-gray-500 font-medium">Meta</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-xl font-bold text-gray-900 dark:text-white" x-text="data.salesAtual"></span>
                        <span class="text-gray-400 font-medium">R$ 200.000,00</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 mt-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 70.8%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Forecast (Grouped Bar Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Previsão de Vendas por Vendedor' : 'Previsão por Fornecedor'"></h3>
                    </div>
                    <x-heroicon-m-ellipsis-vertical class="text-gray-400" style="width: 24px; height: 24px;"/>
                </div>
                <div id="forecastChart" class="w-full h-64"></div>
            </div>

            <!-- Customer Segmentation (Donut Chart) -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Status dos Negócios' : 'Status das Licitações'"></h3>
                    <x-heroicon-m-ellipsis-vertical class="text-gray-400" style="width: 24px; height: 24px;"/>
                </div>
                <div class="w-full flex-grow flex items-center justify-center">
                    <div id="segmentationChart" class="-mt-4"></div>
                </div>
                <div class="mt-4 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-6 rounded-sm bg-emerald-500"></div> Ganho</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white">1.650</span>
                            <span class="text-emerald-500 flex items-center text-xs w-12"><x-heroicon-m-arrow-trending-up class="w-3 h-3"/> 424</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-6 rounded-sm bg-amber-400"></div> Em Andamento</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white">350</span>
                            <span class="text-emerald-500 flex items-center text-xs w-12"><x-heroicon-m-arrow-trending-up class="w-3 h-3"/> 24</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-6 rounded-sm bg-gray-300"></div> Perdido</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white">458</span>
                            <span class="text-red-500 flex items-center text-xs w-12"><x-heroicon-m-arrow-trending-down class="w-3 h-3"/> 213</span>
                        </div>
                    </div>
                    <button class="w-full mt-2 py-2 rounded-lg border border-gray-200 dark:border-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        Mais detalhes
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Últimos Negócios' : 'Últimas Propostas'"></h3>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-m-magnifying-glass class="text-gray-400" style="width: 16px; height: 16px;"/>
                        </div>
                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-700 rounded-lg text-sm bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                    </div>
                    <x-filament::button color="gray" icon="heroicon-m-funnel" outlined>Filter</x-filament::button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4 w-4">
                                <div class="flex items-center">
                                    <input type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3 font-medium">Deal ID</th>
                            <th scope="col" class="px-4 py-3 font-medium" x-text="activeTab === 'comercial' ? 'Vendedor' : 'Fornecedor'"></th>
                            <th scope="col" class="px-4 py-3 font-medium">Produto/Serviço</th>
                            <th scope="col" class="px-4 py-3 font-medium text-right">Valor</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Data</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Fase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded"></td>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">DE124132</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 text-xs flex items-center justify-center font-bold text-gray-600">JD</div>
                                    <span x-text="activeTab === 'comercial' ? 'John Doe' : 'TechCorp Inc.'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-4"><div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> Licença de Software</div></td>
                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">R$ 1.850,00</td>
                            <td class="px-4 py-4 text-center">15/06/2026</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-md dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">Em Negociação</span>
                            </td>
                        </tr>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded"></td>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">DE124133</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 text-xs flex items-center justify-center font-bold text-gray-600">SC</div>
                                    <span x-text="activeTab === 'comercial' ? 'Simon Cyrene' : 'Global Parts'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-4"><div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Consultoria</div></td>
                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">R$ 4.250,00</td>
                            <td class="px-4 py-4 text-center">20/06/2026</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-md dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">Proposta Enviada</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="p-4"><input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded"></td>
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">DE124134</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 text-xs flex items-center justify-center font-bold text-gray-600">JS</div>
                                    <span x-text="activeTab === 'comercial' ? 'Jane Smith' : 'Distribuidora ABC'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-4"><div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Pacote de Suporte</div></td>
                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">R$ 5.500,00</td>
                            <td class="px-4 py-4 text-center">30/05/2026</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-md dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Fechado - Ganho</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script Alpine para Dados -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardData', () => ({
                activeTab: 'comercial',
                comercialData: {
                    conversionRate: '92%',
                    revenue: '15.832',
                    salesAtual: 'R$ 141.600,00',
                    stats: [
                        { label: 'Total Negócios', value: '2.500', growth: '4.9%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>' },
                        { label: 'Total Clientes', value: '1.340', growth: '2.7%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' },
                        { label: 'Receita Total', value: 'R$ 45.567', growth: '8.4%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' },
                        { label: 'Negócios Ganhos', value: '865', growth: '12.2%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>' }
                    ]
                },
                licitacaoData: {
                    conversionRate: '84%',
                    revenue: '45.120',
                    salesAtual: 'R$ 168.400,00',
                    stats: [
                        { label: 'Total Propostas', value: '1.200', growth: '2.1%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' },
                        { label: 'Fornecedores Ativos', value: '342', growth: '5.6%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>' },
                        { label: 'Total Faturado', value: 'R$ 89.230', growth: '11.2%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' },
                        { label: 'Licitações Ganhas', value: '412', growth: '15.3%', icon: '<svg style="width: 24px; height: 24px;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>' }
                    ]
                },
                get data() {
                    return this.activeTab === 'comercial' ? this.comercialData : this.licitacaoData;
                },
                setTab(tab) {
                    this.activeTab = tab;
                    window.dispatchEvent(new CustomEvent('tab-changed', { detail: tab }));
                },
                init() {
                    // Garantir que o DOM está pronto e o script do ApexCharts está carregado
                    this.$nextTick(() => {
                        if (typeof ApexCharts === 'undefined') {
                            let script = document.createElement('script');
                            script.src = "https://cdn.jsdelivr.net/npm/apexcharts";
                            script.onload = () => this.initCharts();
                            document.head.appendChild(script);
                        } else {
                            this.initCharts();
                        }
                    });
                },
                initCharts() {
                    let revChartEl = document.querySelector("#revenueChart");
                    if (!revChartEl || revChartEl.innerHTML !== "") return;

                    // 1. Revenue Insights (Bar Chart Mensal)
                    var revenueOptions = {
                        series: [{ name: 'Receita', data: [15000, 26000, 22000, 18000, 20000, 28000, 32000, 25000, 19000, 21000, 23000, 31000] }],
                        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
                        plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
                        dataLabels: { enabled: false },
                        stroke: { width: 0 },
                        xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'], labels: { style: { colors: '#9ca3af' } }, axisBorder: { show: false }, axisTicks: { show: false } },
                        yaxis: { labels: { style: { colors: '#9ca3af' }, formatter: (val) => "R$ " + (val/1000) + "k" } },
                        fill: { type: 'gradient', gradient: { shade: 'light', type: "vertical", shadeIntensity: 0.25, gradientToColors: ['#3b82f6'], inverseColors: true, opacityFrom: 0.85, opacityTo: 0.15, stops: [50, 100] } },
                        grid: { borderColor: '#f3f4f6', strokeDashArray: 4, yaxis: { lines: { show: true } } },
                        colors: ['#60a5fa']
                    };
                    var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
                    revenueChart.render();

                    // 2. Sales Overview (Semi-circle Gauge)
                    var gaugeOptions = {
                        series: [70.8],
                        chart: { type: 'radialBar', height: 280, offsetY: -20, sparkline: { enabled: true } },
                        plotOptions: { radialBar: { startAngle: -90, endAngle: 90, track: { background: "#e5e7eb", strokeWidth: '97%', margin: 5, dropShadow: { enabled: false } }, dataLabels: { name: { show: false }, value: { offsetY: -2, fontSize: '32px', fontWeight: 'bold' } } } },
                        fill: { type: 'gradient', gradient: { shade: 'light', shadeIntensity: 0.4, inverseColors: false, opacityFrom: 1, opacityTo: 1, stops: [0, 50, 53, 91] } },
                        labels: ['Vendas'],
                        colors: ['#3b82f6']
                    };
                    var gaugeChart = new ApexCharts(document.querySelector("#gaugeChart"), gaugeOptions);
                    gaugeChart.render();

                    // 3. Revenue Forecast (Grouped Bar)
                    var forecastOptions = {
                        series: [{ name: 'Receita', data: [44, 55, 41, 67, 22, 43, 21, 49] }, { name: 'Custo', data: [13, 23, 20, 8, 13, 27, 33, 12] }],
                        chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
                        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 3 } },
                        dataLabels: { enabled: false },
                        stroke: { show: true, width: 2, colors: ['transparent'] },
                        xaxis: { categories: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago'], labels: { style: { colors: '#9ca3af' } } },
                        yaxis: { labels: { style: { colors: '#9ca3af' } } },
                        fill: { opacity: 1 },
                        colors: ['#34d399', '#fbbf24'],
                        legend: { show: false },
                        grid: { borderColor: '#f3f4f6', strokeDashArray: 4 }
                    };
                    var forecastChart = new ApexCharts(document.querySelector("#forecastChart"), forecastOptions);
                    forecastChart.render();

                    // 4. Customer Segmentation (Donut)
                    var segmentationOptions = {
                        series: [1650, 350, 458],
                        chart: { type: 'donut', height: 240, fontFamily: 'inherit' },
                        labels: ['Ganho', 'Em Andamento', 'Perdido'],
                        dataLabels: { enabled: false },
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, name: { show: true, fontSize: '12px', color: '#6b7280' }, value: { show: true, fontSize: '24px', fontWeight: 'bold' }, total: { show: true, showAlways: true, label: 'Total', formatter: function (w) { return "2.458" } } } } } },
                        stroke: { width: 4, colors: ['#ffffff'] },
                        colors: ['#10b981', '#fbbf24', '#d1d5db'],
                        legend: { show: false }
                    };
                    var segmentationChart = new ApexCharts(document.querySelector("#segmentationChart"), segmentationOptions);
                    segmentationChart.render();

                    // Listen to tab changes to update charts data slightly to show interaction
                    window.addEventListener('tab-changed', (e) => {
                        const isComercial = e.detail === 'comercial';
                        
                        // Animação/Mudança nos dados do Forecast
                        forecastChart.updateSeries([
                            { name: 'Receita', data: isComercial ? [44, 55, 41, 67, 22, 43, 21, 49] : [50, 45, 60, 55, 40, 35, 70, 65] },
                            { name: 'Custo', data: isComercial ? [13, 23, 20, 8, 13, 27, 33, 12] : [20, 15, 25, 20, 15, 10, 30, 25] }
                        ]);
                    });
                }
            }));
        });
    </script>
</x-filament-panels::page>
