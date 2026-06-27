<?php

namespace Modules\Comercial\Filament\Pages;

use Filament\Pages\Page;
use Modules\Comercial\Models\TarefaAgenda;
use Illuminate\Support\Carbon;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;

class AgendaSemanaBoard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static \UnitEnum|string|null $navigationGroup = 'Comercial';
    protected string $view = 'comercial::filament.pages.agenda-semana-board';
    protected static ?string $title = 'Atividades';
    protected static ?int $navigationSort = 1;

    public $weekStart;
    public $weekEnd;
    public $days = [];
    public $tempActionData = [];

    // Controles de Visualização Pipedrive
    public $viewMode = 'calendar'; // 'calendar' ou 'list'
    public $activityFilter = 'Tudo';

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
                ->label('+ Atividade')
                ->url(fn (): string => \Modules\Comercial\Filament\Resources\TarefaAgendaResource::getUrl('create'))
                ->color('success'),
        ];
    }

    public function mount()
    {
        $this->weekStart = now()->startOfWeek();
        $this->loadDays();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function goToEdit($id)
    {
        return redirect()->to(\Modules\Comercial\Filament\Resources\TarefaAgendaResource::getUrl('edit', ['record' => $id]));
    }

    public function setFilter($filter)
    {
        $this->activityFilter = $filter;
        $this->loadDays();
    }

    public function loadDays()
    {
        $this->days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $this->weekStart->copy()->addDays($i);
            
            $tarefas = TarefaAgenda::with(['oportunidade', 'oportunidade.fornecedor'])
                ->whereDate('data_inicio', $date->format('Y-m-d'))
                ->get();

            $this->days[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->locale('pt_BR')->translatedFormat('l d'),
                'tarefas' => $tarefas,
            ];
        }
    }

    public function previousWeek()
    {
        $this->weekStart->subWeek();
        $this->loadDays();
    }

    public function nextWeek()
    {
        $this->weekStart->addWeek();
        $this->loadDays();
    }

    public function currentWeek()
    {
        $this->weekStart = now()->startOfWeek();
        $this->loadDays();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TarefaAgenda::query()->with(['oportunidade', 'oportunidade.fornecedor']))
            ->columns([
                IconColumn::make('status')
                    ->label('Concluído')
                    ->icon(fn (string $state): string => match ($state) {
                        'Concluída' => 'heroicon-o-check-circle',
                        default => 'heroicon-o-stop',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Concluída' => 'success',
                        default => 'gray',
                    })
                    ->action(function (TarefaAgenda $record) {
                        $record->update(['status' => $record->status === 'Concluída' ? 'Pendente' : 'Concluída']);
                    }),
                TextColumn::make('titulo')
                    ->label('Assunto')
                    ->searchable()
                    ->sortable()
                    ->color('primary')
                    ->weight('bold'),
                TextColumn::make('oportunidade.titulo')
                    ->label('Negócio')
                    ->searchable()
                    ->sortable()
                    ->color('success'),
                TextColumn::make('oportunidade.pessoa_contato_nome')
                    ->label('Pessoa de contato')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('oportunidade.pessoa_contato_email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('oportunidade.pessoa_contato_telefone')
                    ->label('Telefone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('oportunidade.fornecedor.razao_social')
                    ->label('Organização')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('data_inicio')
                    ->label('Data de vencimento')
                    ->date('d \d\e F')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Atribuído a usuário')
                    ->getStateUsing(fn() => 'Usuário Logado') // Mock do owner por enquanto
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->form(fn (\Filament\Schemas\Schema $form) => \Modules\Comercial\Filament\Resources\TarefaAgendaResource::form($form)->getComponents()),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
