<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Proposta Comercial Nº {{ $proposta->numero ?: $proposta->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            color: #374151;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-size: 13px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            padding: 15mm 20mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            box-sizing: border-box;
            border-top: 5px solid #dc2626;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #282464;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            max-width: 100px;
            max-height: 70px;
            object-fit: contain;
        }

        .company-info {
            border-left: 2px solid #282464;
            padding-left: 15px;
        }

        .company-info h1 {
            color: #282464;
            font-size: 15px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 10px;
            color: #6b7280;
        }

        .header-right {
            text-align: right;
        }

        .header-right h2 {
            color: #282464;
            font-size: 15px;
            margin: 0 0 8px 0;
            text-transform: uppercase;
        }

        .header-right p {
            margin: 3px 0;
            font-size: 10px;
            color: #4b5563;
        }
        
        .header-right .highlight {
            color: #dc2626;
            font-weight: 600;
        }

        .client-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
        }

        .client-col {
            flex: 1;
        }

        .client-col p {
            margin: 5px 0;
            line-height: 1.4;
            font-size: 12px;
        }

        .client-col strong {
            color: #111827;
        }

        .intro-text {
            margin-bottom: 25px;
            line-height: 1.6;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th {
            background-color: #282464 !important;
            color: white !important;
            text-align: left;
            padding: 8px 10px;
            font-size: 11px;
            font-weight: 600;
        }
        
        th.right {
            text-align: right;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
            font-size: 12px;
        }
        
        td.right {
            text-align: right;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            background: white;
        }

        .item-title {
            color: #282464;
            font-weight: 700;
            margin: 0 0 3px 0;
            font-size: 12px;
            text-transform: uppercase;
        }

        .item-subtitle {
            color: #6b7280;
            font-size: 11px;
            margin: 0 0 3px 0;
        }
        
        .item-desc {
            color: #9ca3af;
            font-size: 10px;
            font-style: italic;
            margin: 0;
        }

        .extra-params-box {
            margin: 0 0 10px 0;
            padding: 5px 10px;
        }
        
        .extra-params-title {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 6px;
            color: #111827;
            text-transform: uppercase;
        }
        
        .extra-param-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .extra-param-dots {
            flex-grow: 1;
            border-bottom: 1px dashed #d1d5db;
            margin: 0 10px;
            position: relative;
            top: -4px;
        }
        
        .final-item-price {
            font-weight: 700;
            padding: 10px;
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 11px;
            color: #111827;
        }

        .totals-box {
            margin-left: auto;
            width: 55%;
            margin-bottom: 40px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            font-size: 12px;
            color: #4b5563;
        }

        .total-line.grand-total {
            margin-top: 10px;
            font-size: 13px;
            border-bottom: none;
            color: #111827;
        }

        .page-break {
            page-break-before: always;
        }

        .section-title {
            color: #282464;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 1px solid #282464;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .terms-list {
            padding-left: 20px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 30px;
            font-size: 12px;
        }

        .terms-list li {
            margin-bottom: 5px;
        }
        
        .terms-list li strong {
            color: #374151;
        }

        .obs-box {
            margin-bottom: 40px;
            line-height: 1.6;
            color: #4b5563;
            font-size: 12px;
        }

        .signature-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
        }

        .rep-info h3 {
            color: #282464;
            margin: 0 0 5px 0;
            font-size: 13px;
        }

        .rep-info p {
            margin: 2px 0;
            color: #6b7280;
            font-size: 11px;
        }

        .signature-line {
            text-align: right;
            width: 300px;
        }
        
        .signature-line .line {
            border-bottom: 1px solid #111827;
            margin-bottom: 10px;
        }

        .signature-line p {
            margin: 0;
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            color: #111827;
            font-size: 11px;
        }

        .print-btn-wrapper {
            text-align: center;
            margin: 20px 0;
        }

        .print-btn {
            background-color: #282464;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        
        .print-btn:hover {
            background-color: #1e1b4b;
        }

        @media print {
            body {
                background-color: white;
            }
            .print-container {
                box-shadow: none;
                margin: 0;
                padding: 15mm 0;
                width: 100%;
            }
            .print-btn-wrapper {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-wrapper">
        <button class="print-btn" onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
    </div>

    <div class="print-container">
        
        <!-- Header -->
        <div class="header-top">
            <div class="header-left">
                @if($logoBase64)
                    <img src="data:{{ $logoMime }};base64,{{ $logoBase64 }}" alt="Logo" class="logo">
                @else
                    <div style="width: 80px; height: 80px; background: #e5e7eb; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:10px;">Sem Logo</div>
                @endif
                <div class="company-info">
                    <h1>{{ $settings['nome_fantasia'] ?? $settings['razao_social'] ?? 'EMPRESA NÃO CONFIGURADA' }}</h1>
                    <p>CNPJ: {{ $settings['cnpj'] ?? 'N/A' }}</p>
                    <p>{{ $settings['logradouro'] ?? '' }}, {{ $settings['numero'] ?? 'S/N' }}, {{ $settings['bairro'] ?? '' }}, {{ $settings['cidade'] ?? '' }}-{{ $settings['uf'] ?? '' }}, CEP: {{ $settings['cep'] ?? '' }}</p>
                    <p>
                        @if(!empty($settings['telefone'])) {{ $settings['telefone'] }} @endif 
                        @if(!empty($settings['telefone']) && !empty($settings['celular'])) | @endif 
                        @if(!empty($settings['celular'])) {{ $settings['celular'] }} @endif 
                        @if(!empty($settings['email'])) | {{ $settings['email'] }} @endif
                    </p>
                    <p>{{ $settings['site'] ?? '' }}</p>
                </div>
            </div>
            <div class="header-right">
                <h2>PROPOSTA COMERCIAL</h2>
                <p><strong>Nº</strong> {{ $proposta->numero ?: $proposta->id }}/{{ \Carbon\Carbon::parse($proposta->created_at)->format('Y') }}</p>
                <p>Data de Emissão: <strong>{{ \Carbon\Carbon::parse($proposta->data_proposta)->format('d/m/Y') }}</strong></p>
                <p class="highlight">Validade: {{ \Carbon\Carbon::parse($proposta->validade)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Client Info -->
        <div class="client-box">
            <div class="client-col">
                <p><strong>Cliente:</strong> {{ $proposta->fornecedor->cpf_cnpj ?? '' }} {{ $proposta->fornecedor->cpf_cnpj ? '-' : '' }} {{ $proposta->fornecedor->razao_social ?? $proposta->fornecedor->nome ?? 'Cliente não informado' }}</p>
                <p><strong>CNPJ/CPF:</strong> {{ $proposta->fornecedor->cpf_cnpj ?? 'N/A' }}</p>
                <p><strong>Endereço:</strong> {{ $proposta->fornecedor->endereco ?? '' }}, {{ $proposta->fornecedor->numero ?? 'S/N' }} - {{ $proposta->fornecedor->bairro ?? '' }}</p>
                <p><strong>Cidade/UF:</strong> {{ $proposta->fornecedor->cidade ?? '' }} - {{ $proposta->fornecedor->estado ?? '' }} - CEP: {{ $proposta->fornecedor->cep ?? '' }}</p>
            </div>
            <div class="client-col">
                <p><strong>Contato:</strong> {{ $proposta->oportunidade->pessoa_contato_nome ?? 'N/A' }}</p>
                <p><strong>Telefone:</strong> {{ $proposta->oportunidade->pessoa_contato_telefone ?? 'N/A' }}</p>
                <p><strong>E-mail:</strong> {{ $proposta->oportunidade->pessoa_contato_email ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Intro -->
        <div class="intro-text">
            Prezados (as),<br><br>
            A <strong>{{ $settings['nome_fantasia'] ?? $settings['razao_social'] ?? 'Empresa' }}</strong> agradece seu interesse em nossos produtos e serviços. Sabemos da sua importância em sempre oferecer a mais alta tecnologia para a melhor e mais rápida recuperação do paciente e também em oferecer segurança aos profissionais da saúde.
        </div>

        @php
            $hasLocacao = false;
            $maxMeses = 12;
            $valorTotalMensal = 0;
            $somaItens = 0;
            foreach($proposta->itens as $item) {
                if (($item->tipo ?? '') === 'Locação') {
                    $hasLocacao = true;
                    if (($item->meses_locacao ?? 12) > $maxMeses) {
                        $maxMeses = $item->meses_locacao;
                    }
                    $valorTotalMensal += (($item->valor_unitario ?? 0) * ($item->quantidade ?? 1));
                }
                $somaItens += $item->valor_total ?? 0;
            }
        @endphp

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th width="80">Imagem</th>
                    <th>Descrição</th>
                    <th width="90">Tipo</th>
                    <th width="60">Unid.</th>
                    <th width="40" class="right">Qtd</th>
                    <th width="100" class="right">Vlr. Unit.</th>
                    @if($hasLocacao)
                    <th width="100" class="right">Vlr. Mensal</th>
                    @endif
                    <th width="100" class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($proposta->itens as $item)
                <tr>
                    <td>
                        @php
                            $imgSrc = '';
                            $imgPath = $item->imagem ?? ($item->produto->imagem_path ?? null);
                            if ($imgPath) {
                                $diskLocal = \Illuminate\Support\Facades\Storage::disk('local');
                                $diskPub = \Illuminate\Support\Facades\Storage::disk('public');
                                if ($diskLocal->exists($imgPath)) {
                                    $imgSrc = 'data:'.$diskLocal->mimeType($imgPath).';base64,'.base64_encode($diskLocal->get($imgPath));
                                } elseif ($diskPub->exists($imgPath)) {
                                    $imgSrc = 'data:'.$diskPub->mimeType($imgPath).';base64,'.base64_encode($diskPub->get($imgPath));
                                } else {
                                    $imgSrc = \Illuminate\Support\Facades\Storage::url($imgPath);
                                }
                            }
                        @endphp
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" alt="Produto" class="item-image">
                        @else
                            <div class="item-image" style="display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:10px;">N/A</div>
                        @endif
                    </td>
                    <td>
                        <h4 class="item-title">{{ $item->produto->nome ?? $item->descricao ?? 'Produto/Serviço' }}</h4>
                        @if(!empty($item->modelo))
                            <p class="item-subtitle"><strong>Modelo:</strong> {{ $item->modelo }}</p>
                        @endif
                        @if(!empty($item->produto->fabricante))
                            <p class="item-subtitle">{{ $item->produto->fabricante }} @if(!empty($item->produto->modelo)) - {{ $item->produto->modelo }} @endif</p>
                        @endif
                        @if(!empty($item->descricao_detalhada))
                            <p class="item-desc">{{ $item->descricao_detalhada }}</p>
                        @endif
                    </td>
                    <td>
                        <span style="text-transform: uppercase;">{{ $item->tipo ?? 'Venda' }}</span><br>
                        @if(($item->tipo ?? '') === 'Locação')
                        <span style="font-size: 10px; color: #6b7280;">{{ $item->meses_locacao ?? 12 }} MESES</span>
                        @endif
                    </td>
                    <td>{{ $item->unidade_medida ?? 'Unidade' }}</td>
                    <td class="right">{{ number_format($item->quantidade ?? 1, 0, ',', '.') }}</td>
                    <td class="right">R$ {{ number_format($item->valor_unitario ?? 0, 2, ',', '.') }}</td>
                    @if($hasLocacao)
                    <td class="right">
                        @if(($item->tipo ?? '') === 'Locação')
                            R$ {{ number_format(($item->valor_unitario ?? 0) * ($item->quantidade ?? 1), 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    @endif
                    <td class="right">R$ {{ number_format($item->valor_total ?? 0, 2, ',', '.') }}</td>
                </tr>
                @php 
                    $params = is_string($item->parametros_adicionais) ? json_decode($item->parametros_adicionais, true) : $item->parametros_adicionais;
                @endphp
                @if(!empty($params) && is_array($params) && count($params) > 0)
                <tr class="extra-params-row">
                    <td colspan="{{ $hasLocacao ? 8 : 7 }}">
                        <div class="extra-params-box">
                            <div class="extra-params-title">PARAMETROS ADICIONAIS</div>
                            @php $somaParametros = 0; @endphp
                            @foreach($params as $param)
                            <div class="extra-param-line">
                                <span>{{ $param['nome'] ?? $param['chave'] ?? $param['parametro'] ?? 'Parâmetro' }}</span>
                                <div class="extra-param-dots"></div>
                                @php
                                    $valParam = (float)($param['valor'] ?? $param['preco'] ?? 0);
                                    $somaParametros += $valParam;
                                @endphp
                                <span style="width: 100px; text-align: right;">R$ {{ number_format($valParam, 2, ',', '.') }}</span>
                                <span style="width: 100px; text-align: right; font-weight: 600;">R$ {{ number_format($valParam, 2, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        @if($somaParametros > 0)
                        <div class="final-item-price">
                            <span>VALOR UNITÁRIO FINAL (BASE + ADICIONAIS):</span>
                            <span>R$ {{ number_format(($item->valor_unitario ?? 0) + $somaParametros, 2, ',', '.') }}</span>
                            <span>R$ {{ number_format(($item->valor_total ?? 0) + ($somaParametros * ($item->quantidade ?? 1)), 2, ',', '.') }}</span>
                        </div>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-box">
            @php
                $frete = (float)$proposta->valor_frete;
                // If the proposal total is zero or less than the sum of items, we rely on the dynamic sum of the items.
                // This ensures we never print R$ 0,00 as the grand total when items exist.
                $totalBase = $proposta->valor_total > 0 ? $proposta->valor_total : $somaItens + $frete;
            @endphp
            @if($hasLocacao)
            <div class="total-line">
                <span>VALOR TOTAL MENSAL</span>
                <span>R$ {{ number_format($valorTotalMensal, 2, ',', '.') }}</span>
            </div>
            @else
            <div class="total-line">
                <span>SUBTOTAL DOS ITENS</span>
                <span>R$ {{ number_format($somaItens, 2, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="total-line">
                <span>FRETE ({{ $proposta->tipo_frete ?: 'N/A' }})</span>
                <span>R$ {{ number_format($frete, 2, ',', '.') }}</span>
            </div>
            
            <div class="total-line grand-total">
                @if($hasLocacao)
                <span>VALOR TOTAL DO CONTRATO EM {{ $maxMeses }} MESES</span>
                @else
                <span>VALOR TOTAL GERAL</span>
                @endif
                <span>R$ {{ number_format($totalBase, 2, ',', '.') }}</span>
            </div>
        </div>

        <!-- Page Break -->
        <div class="page-break"></div>

        <!-- Header Page 2 -->
        <div class="header-top">
            <div class="header-left">
                @if($logoBase64)
                    <img src="data:{{ $logoMime }};base64,{{ $logoBase64 }}" alt="Logo" class="logo">
                @else
                    <div style="width: 80px; height: 80px; background: #e5e7eb; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:10px;">Sem Logo</div>
                @endif
                <div class="company-info">
                    <h1>{{ $settings['nome_fantasia'] ?? $settings['razao_social'] ?? 'EMPRESA NÃO CONFIGURADA' }}</h1>
                    <p>CNPJ: {{ $settings['cnpj'] ?? 'N/A' }}</p>
                    <p>{{ $settings['logradouro'] ?? '' }}, {{ $settings['numero'] ?? 'S/N' }}, {{ $settings['bairro'] ?? '' }}, {{ $settings['cidade'] ?? '' }}-{{ $settings['uf'] ?? '' }}, CEP: {{ $settings['cep'] ?? '' }}</p>
                    <p>{{ $settings['site'] ?? '' }}</p>
                </div>
            </div>
            <div class="header-right">
                <h2>PROPOSTA COMERCIAL</h2>
                <p><strong>Nº</strong> {{ $proposta->numero ?: $proposta->id }}/{{ \Carbon\Carbon::parse($proposta->created_at)->format('Y') }}</p>
                <p><strong>Data de Emissão:</strong> {{ \Carbon\Carbon::parse($proposta->data_proposta)->format('d/m/Y') }}</p>
                <p class="highlight">Validade: {{ \Carbon\Carbon::parse($proposta->validade)->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Condições Gerais -->
        <div class="section-title">Condições Gerais de Fornecimento</div>
        @if(!empty($proposta->termos_comerciais))
            <ul class="terms-list">
                @foreach($proposta->termos_comerciais as $termo)
                    @if(is_string($termo))
                        <li>{{ $termo }}</li>
                    @elseif(is_array($termo))
                        <li><strong>{{ $termo['titulo'] ?? $termo['chave'] ?? '' }}:</strong> {{ $termo['descricao'] ?? $termo['valor'] ?? '' }}</li>
                    @endif
                @endforeach
            </ul>
        @else
            <p style="color: #6b7280; margin-bottom: 30px;">Nenhuma condição geral especificada.</p>
        @endif

        <!-- Observações -->
        <div class="section-title">Observações</div>
        <div class="obs-box">
            {!! nl2br(e($proposta->observacoes ?: 'Nenhuma')) !!}
        </div>

        <!-- Signature -->
        <div class="signature-area">
            <div class="rep-info">
                <p style="color:#9ca3af; font-size:10px; margin-bottom: 10px;">RESPONSÁVEL COMERCIAL</p>
                @php $userRep = $proposta->user ?? ($proposta->oportunidade ? $proposta->oportunidade->user : null) ?? auth()->user(); @endphp
                <h3>{{ $userRep->name ?? 'Usuário não vinculado' }}</h3>
                <p>{{ $userRep->cargo_funcao ?? 'COMERCIAL/VENDAS' }}</p>
                <p style="text-transform: none;">Fone: {{ $userRep->telefone ?? 'N/A' }} @if(!empty($userRep->celular)) / Cel: {{ $userRep->celular }} @endif</p>
                <p style="text-transform: none;">E-mail: {{ $userRep->email ?? 'N/A' }}</p>
            </div>
            <div class="signature-line">
                <div class="line"></div>
                <p>
                    <span>Data: ____/____/________</span>
                    <span>De Acordo</span>
                </p>
            </div>
        </div>

        <div style="margin-top: 20px; font-size: 9px; color: #9ca3af; text-align: left;">
            Impresso por {{ auth()->user()->name ?? 'Sistema' }} em {{ now()->format('d/m/Y \à\s H:i') }}
        </div>

    </div>

    <script>
        // Opcional: auto-print
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
