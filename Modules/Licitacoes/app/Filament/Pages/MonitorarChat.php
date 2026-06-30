<?php

namespace Modules\Licitacoes\Filament\Pages;

use Filament\Pages\Page;
use Modules\Licitacoes\Models\Licitacao;
use Modules\Licitacoes\Models\ChatMensagem;

class MonitorarChat extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static \UnitEnum|string|null $navigationGroup = 'Licitações';
    
    protected static ?int $navigationSort = 4;

    protected string $view = 'licitacoes::filament.pages.monitorar-chat';
    
    protected static ?string $title = 'Monitor de Chats';

    public $licitacaoAtivaId = null;
    public $mensagens = [];

    public function mount()
    {
        $primeiraLicitacao = Licitacao::whereIn('status', ['Acolhimento de propostas', 'Em análise'])->first();
        if ($primeiraLicitacao) {
            $this->selecionarLicitacao($primeiraLicitacao->id);
        }
    }

    public function selecionarLicitacao($id)
    {
        $this->licitacaoAtivaId = $id;
        $this->carregarMensagens();
    }

    public function carregarMensagens()
    {
        if ($this->licitacaoAtivaId) {
            $this->mensagens = ChatMensagem::where('licitacao_id', $this->licitacaoAtivaId)
                ->orderBy('data_hora', 'asc')
                ->get();
        }
    }
    
    public function getLicitacoesAtivasProperty()
    {
        return Licitacao::whereIn('status', ['Acolhimento de propostas', 'Em análise'])
            ->withCount(['chatMensagens' => function ($query) {
                $query->where('is_alert', true)->where('lida', false);
            }])
            ->get();
    }
}
