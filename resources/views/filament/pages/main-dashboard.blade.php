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
    <style>
        /* Ajuste do texto central do gráfico de donut para o modo escuro */
        .dark .apexcharts-datalabel-value {
            fill: #ffffff !important;
        }
        .dark .apexcharts-datalabel-label {
            fill: #9ca3af !important;
        }
        .dark .apexcharts-legend-text {
            color: #ffffff !important;
        }
    </style>

    <div x-data="dashboardData()" class="flex flex-col gap-4" x-cloak>
        
        <!-- Header & Tabs Area -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Bem-vindo(a) de volta, Eduardo!
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Abas (Tabs) -->
                <div class="flex bg-white dark:bg-gray-900 rounded-lg p-1 shadow-sm border border-gray-200 dark:border-gray-800">
                    <button 
                        @click="setTab('comercial')" 
                        :class="activeTab === 'comercial' ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="px-3 py-1.5 rounded-md text-sm transition-all duration-200"
                    >
                        Comercial
                    </button>
                    <button 
                        @click="setTab('licitacao')" 
                        :class="activeTab === 'licitacao' ? 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="px-3 py-1.5 rounded-md text-sm transition-all duration-200"
                    >
                        Licitação
                    </button>
                </div>

                <x-filament::button color="gray" icon="heroicon-m-arrow-down-tray" class="bg-gray-900 text-white hover:bg-gray-800 dark:bg-white dark:text-gray-900" size="sm" onclick="window.print()">
                    Exportar
                </x-filament::button>
            </div>
        </div>

        <!-- 1st Row: Custom Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Taxa de Conversão -->
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Taxa de Conversão</h3>
                </div>
                <div class="flex items-end justify-between mb-3">
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white" x-text="data.conversionRate"></span>
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
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Receita de Vendas</h3>
                </div>
                <div class="flex items-end justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xs text-gray-500">R$</span>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white" x-text="data.revenue"></span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Sua receita aumentou este mês em <span class="text-emerald-500 font-semibold">R$ 4.210</span>
                        </p>
                    </div>
                    <!-- Mini Bar Chart placeholder -->
                    <div class="flex items-end gap-1 h-10 flex-shrink-0">
                        <div class="w-2.5 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 40%"></div>
                        <div class="w-2.5 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 60%"></div>
                        <div class="w-2.5 bg-gray-200 dark:bg-gray-700 rounded-sm" style="height: 50%"></div>
                        <div class="w-2.5 bg-blue-500 rounded-sm" style="height: 90%"></div>
                    </div>
                </div>
            </div>

            <!-- Taxa de Retenção/Aprovação -->
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Taxa de Sucesso</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> <span x-text="data.taxaSucesso.ganhoPct"></span>
                                <span class="text-emerald-600 font-normal text-xs flex items-center"><x-heroicon-m-arrow-trending-up style="width: 12px; height: 12px;"/> <span x-text="data.taxaSucesso.ganhoQtd"></span></span>
                            </span>
                            <span class="text-gray-500" x-text="data.taxaSucesso.ganhoLabel"></span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" :style="`width: ${data.taxaSucesso.ganhoPct}`"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-amber-500"></div> <span x-text="data.taxaSucesso.perdidoPct"></span>
                                <span class="text-red-500 font-normal text-xs flex items-center"><x-heroicon-m-arrow-trending-down style="width: 12px; height: 12px;"/> <span x-text="data.taxaSucesso.perdidoQtd"></span></span>
                            </span>
                            <span class="text-gray-500" x-text="data.taxaSucesso.perdidoLabel"></span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" :style="`width: ${data.taxaSucesso.perdidoPct}`"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2nd Row: Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <template x-for="(stat, index) in data.stats" :key="index">
                <div class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="p-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <span x-html="stat.icon" class="[&>svg]:w-4 [&>svg]:h-4"></span>
                        </div>
                        <h4 class="text-xs text-gray-700 dark:text-gray-300 font-medium" x-text="stat.label"></h4>
                    </div>
                    <div class="text-xl font-bold text-gray-900 dark:text-white mb-2" x-text="stat.value"></div>
                    <div class="flex items-center gap-1.5 text-xs">
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Revenue Insights (Bar Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Receita Mensal</h3>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span class="text-xl font-bold text-gray-900 dark:text-white" x-text="data.salesAtual"></span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-xs font-medium flex items-center gap-1"><x-heroicon-m-arrow-up style="width: 10px; height: 10px;"/> 0%</span>
                        </div>
                    </div>
                </div>
                <div id="revenueChart" class="w-full h-56"></div>
            </div>

            <!-- Sales Overview (Gauge) -->
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Metas de Vendas</h3>
                </div>
                <div id="gaugeChart" class="w-full flex-grow flex items-center justify-center mt-2 -mb-8"></div>
                <div class="mt-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500 font-medium">Atual</span>
                        <span class="text-gray-500 font-medium">Meta</span>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="data.salesAtual"></span>
                        <span class="text-sm text-gray-400 font-medium">R$ 200.000,00</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 mt-2">
                        <div class="bg-blue-500 h-1.5 rounded-full" :style="`width: ${data.gaugeMeta}%`"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Revenue Forecast (Grouped Bar Chart) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Previsão de Vendas por Vendedor' : 'Previsão por Fornecedor'"></h3>
                    </div>
                </div>
                <div id="forecastChart" class="w-full h-56"></div>
            </div>

            <!-- Customer Segmentation (Donut Chart) -->
            <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Status dos Negócios' : 'Status das Licitações'"></h3>
                </div>
                <div class="w-full flex-grow flex items-center justify-center">
                    <div id="segmentationChart" class="-mt-4"></div>
                </div>
                <div class="mt-4 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-4 rounded-sm bg-emerald-500"></div> Ganho</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white" x-text="data.chartStatus.ganho"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-4 rounded-sm bg-amber-400"></div> Em Andamento</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white" x-text="data.chartStatus.andamento"></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400"><div class="w-1.5 h-4 rounded-sm bg-gray-300"></div> Perdido</span>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-gray-900 dark:text-white" x-text="data.chartStatus.perdido"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-900 p-5 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
                <h3 class="font-semibold text-gray-900 dark:text-white" x-text="activeTab === 'comercial' ? 'Últimos Negócios Ganhos' : 'Últimas Licitações'"></h3>
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
                            <th scope="col" class="px-4 py-3 font-medium">Deal ID</th>
                            <th scope="col" class="px-4 py-3 font-medium" x-text="activeTab === 'comercial' ? 'Vendedor' : 'Fornecedor'"></th>
                            <th scope="col" class="px-4 py-3 font-medium">Produto/Serviço</th>
                            <th scope="col" class="px-4 py-3 font-medium text-right">Valor</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Data</th>
                            <th scope="col" class="px-4 py-3 font-medium text-center">Fase</th>
                        </tr>
                    </thead>
                    <tbody x-show="activeTab === 'comercial'">
                        @forelse($ultimosNegocios as $negocio)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">OP-{{ $negocio->id }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 text-xs flex items-center justify-center font-bold text-gray-600">
                                        {{ substr($negocio->user->name ?? 'U', 0, 2) }}
                                    </div>
                                    <span>{{ $negocio->user->name ?? 'Sem Vendedor' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4"><div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> {{ \Illuminate\Support\Str::limit($negocio->titulo, 25) }}</div></td>
                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">R$ {{ number_format($negocio->valor_estimado, 2, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center">{{ $negocio->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-md dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">{{ ucfirst($negocio->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Nenhum negócio encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tbody x-show="activeTab === 'licitacao'" x-cloak>
                        @forelse($ultimasLicitacoes as $licitacao)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">LIC-{{ $licitacao->id }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span>{{ \Illuminate\Support\Str::limit($licitacao->orgao_razao_social ?? 'Não Informado', 25) }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4"><div class="flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> {{ \Illuminate\Support\Str::limit($licitacao->objeto, 25) }}</div></td>
                            <td class="px-4 py-4 text-right font-medium text-gray-900 dark:text-white">R$ {{ number_format($licitacao->itens->sum('valor_total'), 2, ',', '.') }}</td>
                            <td class="px-4 py-4 text-center">{{ $licitacao->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-md dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">{{ ucfirst($licitacao->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">Nenhuma licitação encontrada.</td>
                        </tr>
                        @endforelse
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
                comercialData: @json($comercialData),
                licitacaoData: @json($licitacaoData),
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

                    // 1. Revenue Insights (Bar Chart)
                    var revenueOptions = {
                        series: [{ name: 'Receita', data: this.data.chartReceitaMensal }],
                        chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'inherit' },
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
                        series: [this.data.gaugeMeta],
                        chart: { type: 'radialBar', height: 220, offsetY: 10, sparkline: { enabled: true } },
                        plotOptions: { radialBar: { startAngle: -90, endAngle: 90, track: { background: "#e5e7eb", strokeWidth: '97%', margin: 5, dropShadow: { enabled: false } }, dataLabels: { name: { show: false }, value: { offsetY: -2, fontSize: '32px', fontWeight: 'bold' } } } },
                        fill: { type: 'gradient', gradient: { shade: 'light', shadeIntensity: 0.4, inverseColors: false, opacityFrom: 1, opacityTo: 1, stops: [0, 50, 53, 91] } },
                        labels: ['Vendas'],
                        colors: ['#3b82f6']
                    };
                    var gaugeChart = new ApexCharts(document.querySelector("#gaugeChart"), gaugeOptions);
                    gaugeChart.render();

                    // 3. Revenue Forecast (Grouped Bar)
                    var forecastOptions = {
                        series: [{ name: 'Receita', data: this.data.chartValues.length ? this.data.chartValues : [0] }],
                        chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'inherit' },
                        plotOptions: { bar: { horizontal: false, columnWidth: '55%', borderRadius: 3 } },
                        dataLabels: { enabled: false },
                        stroke: { show: true, width: 2, colors: ['transparent'] },
                        xaxis: { categories: this.data.chartCategories.length ? this.data.chartCategories : ['-'], labels: { style: { colors: '#9ca3af' } } },
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
                        series: [this.data.chartStatus.ganho, this.data.chartStatus.andamento, this.data.chartStatus.perdido],
                        chart: { type: 'donut', height: 200, fontFamily: 'inherit' },
                        labels: ['Ganho', 'Em Andamento', 'Perdido'],
                        dataLabels: { enabled: false },
                        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, name: { show: true, fontSize: '12px', color: '#6b7280' }, value: { show: true, fontSize: '24px', fontWeight: 'bold' }, total: { show: true, showAlways: true, label: 'Total', formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0) } } } } } },
                        stroke: { width: 0 },
                        colors: ['#10b981', '#fbbf24', '#d1d5db'],
                        legend: { show: false }
                    };
                    var segmentationChart = new ApexCharts(document.querySelector("#segmentationChart"), segmentationOptions);
                    segmentationChart.render();

                    // Listen to tab changes to update charts data slightly to show interaction
                    window.addEventListener('tab-changed', (e) => {
                        const newData = e.detail === 'comercial' ? this.comercialData : this.licitacaoData;
                        
                        revenueChart.updateSeries([{ name: 'Receita', data: newData.chartReceitaMensal }]);
                        gaugeChart.updateSeries([newData.gaugeMeta]);

                        forecastChart.updateSeries([
                            { name: 'Receita', data: newData.chartValues.length ? newData.chartValues : [0] }
                        ]);
                        forecastChart.updateOptions({
                            xaxis: { categories: newData.chartCategories.length ? newData.chartCategories : ['-'] }
                        });

                        segmentationChart.updateSeries([
                            newData.chartStatus.ganho,
                            newData.chartStatus.andamento,
                            newData.chartStatus.perdido
                        ]);
                    });
                }
            }));
        });
    </script>
</x-filament-panels::page>
