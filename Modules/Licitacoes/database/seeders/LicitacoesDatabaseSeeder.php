<?php

namespace Modules\Licitacoes\Database\Seeders;

use Illuminate\Database\Seeder;

class LicitacoesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Modules\Licitacoes\Models\PerfilBusca::firstOrCreate([
            'nome' => 'Perfil Nacional Saúde',
        ], [
            'estados' => ['SP', 'RJ', 'MG', 'DF'],
            'palavras_chave' => ['saude', 'hospital', 'medicamento', 'equipamento', 'ambulatorio', 'clinico', 'leito'],
            'modalidades' => ['Pregão Eletrônico', 'Concorrência'],
            'ativo' => true,
        ]);
        
        // Também cria algumas mensagens de chat fake para teste de UI se a tabela estiver vazia
        if (\Modules\Licitacoes\Models\ChatMensagem::count() === 0 && \Modules\Licitacoes\Models\Licitacao::count() > 0) {
            $licitacaoId = \Modules\Licitacoes\Models\Licitacao::first()->id;
            \Modules\Licitacoes\Models\ChatMensagem::create([
                'licitacao_id' => $licitacaoId,
                'tipo' => 'SESSÃO',
                'texto' => 'A sessão pública está aberta.',
                'data_hora' => now()->subMinutes(30),
                'is_alert' => false,
            ]);
            \Modules\Licitacoes\Models\ChatMensagem::create([
                'licitacao_id' => $licitacaoId,
                'tipo' => 'MENSAGEM',
                'texto' => 'Pregoeiro informa: Iminência de encerramento da etapa de lances.',
                'data_hora' => now()->subMinutes(5),
                'is_alert' => true,
                'keyword_encontrada' => 'iminência',
            ]);
        }
    }
}
