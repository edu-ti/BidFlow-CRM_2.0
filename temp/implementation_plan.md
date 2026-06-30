# Integração de Dados Reais nas Páginas de Licitações

Este documento detalha o plano técnico de como daremos "vida" às páginas que criamos, conectando os recursos visuais com motores de busca reais e integrando-os ao CRM de forma automática.

## Fluxo de Arquitetura

1. **Configuração do Robô de Busca:** O usuário definirá filtros (palavras-chave, estados, órgãos) em uma nova página de Configurações.
2. **Coleta de Dados:** Scripts rodarão de madrugada (Cron Jobs) varrendo os portais (PNCP, Compras.gov.br, BNC, Licitar Digital, etc).
3. **Armazenamento Local:** Todos os resultados brutos caem na tabela `oportunidades_licitacoes`.
4. **Boletins Automáticos:** Ao fim da coleta, o sistema gera o modelo `Boletim` do dia e atrela as licitações diárias a ele.
5. **Migração para o CRM:** O usuário vê os boletins e licitações e, com um botão "Importar", a oportunidade é copiada para a tabela real `licitacoes` (Gerenciar Pregões), onde passa a ser tratada pelo funil de vendas, propostas e lances.

---

## Proposed Changes (Fases de Implementação)

### 1. Novo Recurso: Configurações de Busca (Filtros do Robô)

Precisamos de uma página onde o usuário informa o que o sistema deve procurar:
- **Palavras-chave Inclusivas:** (ex: "Medicamentos", "Software", "Veículos")
- **Palavras-chave Exclusivas:** (ex: "Construção civil")
- **Portais de Interesse:** Checkboxes com os portais desejados.
- **Localidade:** Estados (UF) alvo.

#### [NEW] `Modules\Licitacoes\Models\ConfiguracaoBusca`
#### [NEW] `Modules\Licitacoes\Filament\Pages\ConfiguracoesRobo`

---

### 2. Integração com APIs e Fontes Reais

Criaremos classes Services responsáveis por conectar e baixar dados. Começaremos pelo **PNCP (Portal Nacional de Contratações Públicas)** que engloba a grande maioria das compras públicas do Brasil.

#### [NEW] `Modules\Licitacoes\Services\Integracoes\PncpService`
*Responsável por consumir a API aberta do PNCP utilizando os filtros configurados pelo usuário, parsear o JSON de resposta e salvar na tabela `oportunidades_licitacoes`.*

#### [NEW] `Modules\Licitacoes\Services\Integracoes\ComprasGovService`
*Responsável por buscar pregões do painel do Compras.gov (antigo Comprasnet) e Licitações-e (Banco do Brasil).*

---

### 3. Rotina Automática (Cron Jobs & Filas)

Para que as páginas "Boletins" e "Encontrar Licitações" já amanheçam cheias de dados, precisamos de um *Scheduler* do Laravel.

#### [NEW] `Modules\Licitacoes\Console\Commands\BuscarNovasLicitacoesCommand`
*Comando Laravel (`php artisan licitacoes:buscar`) que será disparado toda madrugada (ex: 03:00).*

- **Fluxo do Comando:** 
  1. Lê os filtros da base.
  2. Aciona o `PncpService` e outros.
  3. Salva no banco.
  4. Gera um `Boletim` com as novidades do dia.

---

### 4. Integração nas Páginas Existentes

Vamos refinar as páginas que já criamos para consumir esses dados.

#### [MODIFY] `Modules\Licitacoes\Filament\Resources\OportunidadeLicitacaoResource` (Encontrar Licitações)
- Passará a ler e listar diretamente os registros da tabela `oportunidades_licitacoes`.
- Adicionaremos a Ação de Tabela: **"Importar para CRM"**.

#### [MODIFY] `Modules\Licitacoes\Filament\Pages\Boletins` (Calendário)
- Os dias marcados no calendário farão uma query na tabela `boletins` buscando os boletins gerados pelo *Command*.
- Ao clicar no dia, abrirá a lista real amarrada à tabela pivot `boletim_oportunidade`.

#### [MODIFY] `Modules\Licitacoes\Filament\Resources\LicitacaoResource` (Gerenciar Pregões)
- **Ação Global / Action Importadora:** O método que vai converter uma `OportunidadeLicitacao` em uma `Licitacao`, criando os `Itens` automaticamente a partir dos dados do edital baixado (quando disponível via integração).

---

## User Review Required

> [!IMPORTANT]
> **Definição de Escopo de API:** 
> Para darmos andamento, o correto é começarmos pela API do **PNCP (Portal Nacional de Contratações Públicas)** que já centraliza muitos dos sistemas regionais e o Compras.gov.br. Você concorda em implementarmos a primeira versão do motor de busca usando o PNCP?
>
> Em seguida, precisamos definir qual a ferramenta que você está rodando ou usará no servidor para gerenciar as "rotinas da madrugada" (CRON). Como é seu servidor hoje?

## Open Questions

1. O botão **"Importar para o CRM"** (que joga a licitação encontrada no robô para o seu painel de "Gerenciar Pregões") deve já tentar buscar e cadastrar todos os Lotes/Itens automaticamente ou criará apenas a licitação inicial (cabeçalho) e o usuário preenche os itens manualmente depois?
2. Além de UF e Palavras-chave, tem mais algum filtro que você considera crítico que o robô respeite de madrugada?
