<x-filament-panels::page>
    @if($boletimAtivo)
        <!-- Visão: Licitações e Acompanhamentos (Cards) -->
        <div style="margin-bottom: 1rem;">
            <button wire:click="selecionarBoletim(null)" style="background: none; border: none; color: #2563eb; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; font-size: 0.875rem;">
                <x-filament::icon icon="heroicon-o-arrow-left" style="width: 1rem; height: 1rem;" /> Voltar para o Calendário
            </button>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; background: white; padding: 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
            <div style="font-size: 1.25rem; font-weight: bold; color: #1f2937;">
                Boletim: {{ \Carbon\Carbon::parse($boletimAtivo->data_geracao)->format('d/m/Y') }}
            </div>
            <div style="font-size: 0.875rem; color: #6b7280;">
                Total: {{ $oportunidades->count() }} licitações
            </div>
        </div>
        
        <div>
            @forelse($oportunidades as $index => $licitacao)
                @include('licitacoes::components.licitacao-card', ['licitacao' => $licitacao, 'index' => $index + 1, 'isGerenciada' => false])
            @empty
                <div style="text-align: center; color: #6b7280; padding: 3rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                    Nenhuma licitação encontrada neste boletim.
                </div>
            @endforelse
        </div>

    @else
        <!-- Visão: Calendário (Boletins de mês e ano) -->
        @php
            $today = \Carbon\Carbon::now();
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();
            $startOfWeek = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $endOfWeek = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            
            $days = [];
            for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
                $boletim = $boletinsDisponiveis->firstWhere(function($b) use ($date) {
                    return \Carbon\Carbon::parse($b->data_geracao)->isSameDay($date);
                });
                $days[] = [
                    'date' => $date->copy(),
                    'isCurrentMonth' => $date->isSameMonth($today),
                    'isToday' => $date->isToday(),
                    'boletim' => $boletim,
                ];
            }
        @endphp

        <div style="background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb; overflow: hidden;">
            <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb; text-align: center; font-size: 1.25rem; font-weight: bold; background: #f9fafb;">
                {{ ucfirst($today->translatedFormat('F / Y')) }}
            </div>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; border-bottom: 1px solid #e5e7eb; background: #f3f4f6; font-weight: 600; color: #374151;">
                <div style="padding: 0.5rem;">Dom</div>
                <div style="padding: 0.5rem;">Seg</div>
                <div style="padding: 0.5rem;">Ter</div>
                <div style="padding: 0.5rem;">Qua</div>
                <div style="padding: 0.5rem;">Qui</div>
                <div style="padding: 0.5rem;">Sex</div>
                <div style="padding: 0.5rem;">Sáb</div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e5e7eb;">
                @foreach($days as $day)
                    <div style="min-height: 100px; background: {{ $day['isCurrentMonth'] ? 'white' : '#f9fafb' }}; padding: 0.5rem; display: flex; flex-direction: column;">
                        <div style="text-align: right; font-size: 0.875rem; color: {{ $day['isToday'] ? '#2563eb' : ($day['isCurrentMonth'] ? '#374151' : '#9ca3af') }}; font-weight: {{ $day['isToday'] ? 'bold' : 'normal' }};">
                            {{ $day['date']->format('d') }}
                        </div>
                        <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                            @if($day['boletim'])
                                <button wire:click="selecionarBoletim({{ $day['boletim']->id }})" style="background: #10b981; color: white; border: none; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: bold; cursor: pointer; width: 100%; text-align: center;">
                                    {{ $day['boletim']->oportunidades()->count() }} Oportunidades
                                </button>
                            @else
                                <span style="font-size: 0.75rem; color: #d1d5db;">-</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-filament-panels::page>
