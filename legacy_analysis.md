# Análise do Sistema Legado (CRM-FRPE)

Após analisar a documentação gerada pelo framework **Reversa** no diretório `_reversa_sdd/`, extraí os pontos principais sobre como o sistema anterior funcionava. Esta análise serve como base para entendermos o que precisará ser migrado, modernizado ou descartado na nova versão em Laravel/Filament.

## 🏛 Arquitetura e Stack Legada
O sistema legado era uma aplicação **monolítica** clássica:
- **Backend:** PHP Puro (Vanilla) focado no arquivo `api.php` que agia como um Front Controller, roteando requisições AJAX para a pasta `api/handlers/`. Não utilizava ORM; todas as consultas ao MySQL eram feitas via PDO com SQL "cru" e muitos `UNION ALL`.
- **Frontend:** HTML/CSS/JS puros, altamente dependente de bibliotecas locais em `/assets/vendor`.
- **Relatórios & Arquivos:** Uso de `PhpSpreadsheet` para exportação Excel nativa, envio de e-mails via PHPMailer/SendGrid, e extração de dados de NFs via `smalot/pdfparser`.
- **Dívida Técnica:** Sem cobertura de testes automatizados, lógica de negócios misturada com banco de dados, e acoplamento rígido (ex: Propostas injetando dados diretamente nas tabelas de Fornecedores via transaction).

---

## 🗄 Modelo de Dados e Domínio
A espinha dorsal do sistema girava em torno do ciclo completo de **Vendas (CRM)**:

1. **Gestão de Pessoas e Acessos:** `usuarios` com controle de permissões dinâmico via matriz RBAC (`roles`, `permissions`, `role_permissions`). O sistema tinha um *Soft Delete* robusto para preservar histórico.
2. **Ciclo Inicial:** `leads` importados ou cadastrados podiam ser convertidos em clientes (`clientes_pf` ou `organizacoes` PJ) e contatos (`contatos`).
3. **Oportunidades e Funil:** `oportunidades` (pré-propostas) passavam por etapas do funil (`etapas_funil`) e possuíam itens com precificação dinâmica.
4. **Propostas e Catálogo:** `propostas` com versionamento de preço, formadas por `produtos` e `kits` (onde o kit "congelava" o preço na hora da venda). Havia cálculos complexos para "meses de locação".
5. **Integração Comercial-Fornecedor:** A aprovação de uma proposta disparava gatilhos para injetar comissionamentos em `vendas_fornecedores`.
6. **Agendamentos:** Controle de tarefas da equipe (`agendamentos`) que alterava o status das oportunidades conforme o controle de entregas.
7. **Pós-venda e Financeiro:** Cadastro de `empenhos` e `notas_fiscais` vinculadas às oportunidades.

---

## ⚙️ Regras de Negócio Críticas (Atenção para a Migração)

> [!WARNING]
> Estas regras devem ser tratadas com cuidado na nova arquitetura, pois garantem a continuidade da operação de vendas e comissionamentos.

1. **Scoped Data (Visão por Papel):** Vendedores só podiam ver e operar seus próprios Leads, Oportunidades e Propostas. Já Gestores/Analistas tinham visão global. O legado filtrava isso direto nas queries SQL brutas. No Filament, usaremos *Eloquent Scopes*.
2. **Sincronização Proposta-Oportunidade:** Mudar o status de uma Proposta para "Aprovada" tem que movimentar a Oportunidade automaticamente para "Ganho/Fechado" no funil Kanban.
3. **Geração Automática de Vendas:** Quando uma Proposta é aprovada, o sistema criava automaticamente um registro em `vendas_fornecedores` (Módulo de Licitações/Fornecedores) para fins de comissionamento ou fechamento.
4. **Leitura Automática de Notas Fiscais:** O sistema utilizava RegEx e a biblioteca PDFParser para ler as NFs de fornecedores, extrair o valor/destinatário e atualizar a oportunidade sozinha.

---

## 🚀 O que já fizemos vs. O que falta?

Analisando a estrutura legada e comparando com os módulos que criamos até agora (Módulo `Fornecedores`, `Licitações` e `Comercial` com o CRM inicial):

* **Feito:** Cadastro de Fornecedores, Produtos simples, Oportunidades, Agenda (Tarefas) e Propostas.
* **Próximos Passos Sugeridos (Baseados no legado):**
  - **Módulo Financeiro:** Criar as entidades `empenhos` e `notas_fiscais` para anexar faturamentos pós-venda.
  - **Kits e Tabelas de Preço:** Evoluir nosso cadastro simples de Produto para suportar Agrupamentos (Kits) e versionamento de preços (snapshot no momento da proposta).
  - **Robô de NF (Parser):** Recriar a leitura automática de Notas Fiscais, integrando alguma lib de leitura de PDF do PHP ao Laravel.
  - **Gatilhos de Automação (Sincronização Proposta > Oportunidade):** Fazer as amarrações para que quando uma proposta for aceita, a oportunidade ande sozinha no Kanban e gere os registros financeiros/comissionamento.
  - **Módulo de Leads / Captura:** Criar funil de pré-qualificação antes de virar uma Oportunidade.
