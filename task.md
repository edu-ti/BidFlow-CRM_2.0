# Lista de Tarefas: Módulo Licitações

## 1. Configuração Filament Modular
- `[x]` Atualizar `AdminPanelProvider.php` para auto-discovery de recursos nos módulos.

## 2. Banco de Dados e Modelos
- `[x]` Criar migration de `Licitacoes` com os campos aprovados.
- `[x]` Criar Model `Licitacao` (`Modules/Licitacoes/app/Models/Licitacao.php`).
- `[x]` Executar `artisan migrate` pelo Docker.

## 3. Interface Administrativa (Filament)
- `[x]` Criar `LicitacaoResource` (Resource, Pages).
- `[x]` Configurar Tabela (`table()`) com colunas e filtros.
- `[x]` Configurar Formulário (`form()`) com inputs, selects e máscaras.

## 4. Validação
- `[ ]` Cadastrar uma Licitação pelo painel.
