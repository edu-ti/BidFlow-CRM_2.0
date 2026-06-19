<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Proposta Comercial - {{ $licitacao->numero }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0056b3;
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .info-section h3 {
            margin-top: 0;
            font-size: 14px;
            color: #0056b3;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-row strong {
            display: inline-block;
            width: 120px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        table.items th, table.items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table.items th {
            background-color: #0056b3;
            color: white;
            font-weight: bold;
        }
        table.items .text-right {
            text-align: right;
        }
        table.items .text-center {
            text-align: center;
        }
        .total-row th {
            background-color: #f1f1f1;
            color: #333;
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            font-size: 11px;
        }
        .signature-box {
            margin-top: 50px;
            text-align: center;
            width: 50%;
            float: right;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
            padding-top: 5px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>NOME DA SUA EMPRESA LTDA</h1>
        <p>CNPJ: 00.000.000/0001-00 | IE: 123.456.789</p>
        <p>Av. Exemplo Fictício, 123 - Centro, Cidade - UF</p>
        <p>Telefone: (11) 9999-9999 | Email: contato@suaempresa.com.br</p>
    </div>

    <h2 style="text-align: center;">PROPOSTA COMERCIAL</h2>

    <div class="info-section">
        <h3>Dados do Órgão / Cliente</h3>
        <div class="info-row"><strong>Órgão Licitante:</strong> {{ $licitacao->orgao_licitante }}</div>
        <div class="info-row"><strong>CNPJ do Órgão:</strong> {{ $licitacao->orgao_cnpj ?? 'Não informado' }}</div>
        <div class="info-row"><strong>Modalidade:</strong> {{ $licitacao->modalidade }} Nº {{ $licitacao->numero }}</div>
        <div class="info-row"><strong>Data de Abertura:</strong> {{ \Carbon\Carbon::parse($licitacao->data_hora_abertura)->format('d/m/Y H:i') }}</div>
    </div>

    <div class="info-section">
        <h3>Objeto da Licitação</h3>
        <p style="margin: 0; text-align: justify;">{{ $licitacao->objeto }}</p>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th class="text-center" width="5%">Item</th>
                <th width="40%">Descrição</th>
                <th width="20%">Marca / Modelo</th>
                <th class="text-center" width="10%">Qtd</th>
                <th class="text-right" width="12%">V. Unit.</th>
                <th class="text-right" width="13%">V. Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeral = 0; @endphp
            @forelse($licitacao->itens as $item)
                @php $totalGeral += (float) $item->valor_total; @endphp
                <tr>
                    <td class="text-center">{{ $item->numero_item }}</td>
                    <td>{{ $item->descricao }}</td>
                    <td>
                        {{ $item->marca ?? '-' }} 
                        @if($item->modelo) / {{ $item->modelo }} @endif
                    </td>
                    <td class="text-center">{{ $item->quantidade }}</td>
                    <td class="text-right">R$ {{ number_format((float) $item->valor_unitario, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format((float) $item->valor_total, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum item cadastrado para esta proposta.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5">VALOR TOTAL GERAL</th>
                <th class="text-right">R$ {{ number_format($totalGeral, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p><strong>Condições Gerais da Proposta:</strong></p>
        <ul>
            <li><strong>Validade da Proposta:</strong> 60 (sessenta) dias corridos a partir da data de apresentação.</li>
            <li><strong>Prazo de Entrega:</strong> Conforme estabelecido em edital.</li>
            <li><strong>Condições de Pagamento:</strong> Conforme termo de referência/edital, com depósito em conta corrente.</li>
            <li><strong>Garantia:</strong> Garantia legal do fabricante para os produtos ofertados.</li>
            <li><strong>Dados Bancários:</strong> Banco Fictício (000), Agência: 1234, Conta: 12345-6.</li>
        </ul>

        <div class="signature-box">
            <div class="signature-line">
                <strong>Nome do Responsável Legal</strong><br>
                Cargo do Responsável<br>
                NOME DA SUA EMPRESA LTDA
            </div>
        </div>
        <div class="clear"></div>
        <p style="text-align: right; margin-top: 20px;">
            Gerado em {{ now()->format('d/m/Y \à\s H:i') }}
        </p>
    </div>
</div>

</body>
</html>
