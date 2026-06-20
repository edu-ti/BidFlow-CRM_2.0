# Lista de Tarefas: Módulo Licitações

## 1. Configuração Filament Modular
- `[x]` Atualizar `AdminPanelProvider.php` para auto-discovery de recursos nos módulos.

## 2. Banco de Dados e Modelos
- [x] Criar migration para adicionar `oportunidade_id` em `proposta_comercials`.
- [ ] Rodar as migrations.
- [x] Atualizar model `Oportunidade` com relação `propostas()` e gatekeepers de exclusão/update.
- [x] Atualizar model `PropostaComercial` com relação `oportunidade()`.
- [x] Criar evento/observer `PropostaComercialObserver` para sincronizar Funil e Handoff.
- [x] Registrar o Observer.
- [x] Atualizar `OportunidadeResource.php` (Gatekeepers de transição de fase).
- [x] Criar `PropostasRelationManager` para as Oportunidades.
- [x] Atualizar `PropostaComercialResource.php` para incluir `oportunidade_id`.
- [x] Atualizar cálculo de valor da Oportunidade com base nas Propostas.

## 3. Interface Administrativa (Filament)
- `[x]` Criar `LicitacaoResource` (Resource, Pages).
- `[x]` Configurar Tabela (`table()`) com colunas e filtros.
- `[x]` Configurar Formulário (`form()`) com inputs, selects e máscaras.

## 4. Validação
- `[x]` Cadastrar uma Licitação pelo painel.
