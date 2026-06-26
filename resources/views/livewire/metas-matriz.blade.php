<div>
    <style>
        .matriz-container { margin-bottom: 2rem; }
        .matriz-card { background: white; border: 1px solid #10b981; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 1.5rem; position: relative; }
        .matriz-header { background: #ecfdf5; padding: 0.75rem 1rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #a7f3d0; }
        .matriz-actions { display: flex; align-items: center; gap: 0.5rem; }
        .matriz-select { font-size: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.25rem; padding: 0.25rem; }
        .matriz-btn-add { background: white; border: 1px solid #6ee7b7; color: #059669; font-size: 0.75rem; font-weight: bold; padding: 0.25rem 0.5rem; border-radius: 0.25rem; cursor: pointer; }
        .matriz-btn-add:hover { background: #d1fae5; }
        
        .matriz-scroll { overflow-x: auto; }
        .matriz-table { table-layout: fixed; width: 100%; min-width: 900px; border-collapse: collapse; }
        .matriz-th { padding: 0.5rem; text-align: center; font-size: 0.65rem; font-weight: bold; text-transform: uppercase; color: #6b7280; }
        .matriz-th.estado-col { width: 100px; text-align: left; position: sticky; left: 0; background: white; border-right: 1px solid #e5e7eb; z-index: 10; }
        
        .matriz-tr:hover { background: #f9fafb; }
        .matriz-td { padding: 0.25rem; border-top: 1px solid #f3f4f6; }
        .matriz-td.estado-col { position: sticky; left: 0; background: white; border-right: 1px solid #e5e7eb; z-index: 10; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem; }
        
        .matriz-tag { display: inline-flex; align-items: center; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: bold; background: #dbeafe; color: #1e40af; }
        .matriz-remove { color: #f87171; cursor: pointer; background: none; border: none; font-size: 0.8rem; display: none; }
        .matriz-tr:hover .matriz-remove { display: inline-block; }
        
        .matriz-input { width: 100%; text-align: right; font-size: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.25rem; padding: 0.25rem; }
        .matriz-input:focus { border-color: #10b981; outline: none; box-shadow: 0 0 0 1px #10b981; }
        
        .matriz-total { font-size: 0.75rem; font-weight: bold; color: #059669; }
    </style>

    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <span style="font-weight: bold; color: #374151; font-size: 0.875rem; letter-spacing: 0.025em;">ANO BASE:</span>
            <select wire:model.live="anoBase" style="padding: 0.5rem; border-radius: 0.375rem; border: 1px solid #d1d5db; font-weight: 600;">
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>
            <button type="button" wire:click="loadData" style="padding: 0.5rem 1rem; background: #f3f4f6; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; border: 1px solid #d1d5db; display: flex; align-items: center; gap: 0.5rem;">
                <x-heroicon-o-arrow-path style="width: 1rem; height: 1rem;" /> Recarregar
            </button>
        </div>
    </div>

    <!-- Metas e Comissões por Vendedor -->
    <div class="mb-8">
        <h3 style="font-size: 0.875rem; font-weight: bold; color: #ea580c; text-transform: uppercase; margin-bottom: 1rem;">
            Metas e Comissões por Vendedor
        </h3>
        
        <div style="background: white; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #ea580c; color: white;">
                    <tr>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Vendedor</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Meta (R$)</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Fixo (R$)</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">% Com.</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; text-align: center;">Ativo</th>
                        <th style="padding: 0.75rem 1rem; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendedores as $i => $v)
                    <tr style="border-top: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem 1rem;">
                            <select wire:model="vendedores.{{$i}}.user_id" style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500;">
                                <option value="">Selecione...</option>
                                @foreach($availableVendedores as $av)
                                    <option value="{{$av['id']}}">{{$av['name']}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding: 0.5rem 1rem;">
                            <input type="number" wire:model="vendedores.{{$i}}.valor" style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;" />
                        </td>
                        <td style="padding: 0.5rem 1rem;">
                            <input type="number" wire:model="vendedores.{{$i}}.fixo" style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;" />
                        </td>
                        <td style="padding: 0.5rem 1rem;">
                            <input type="number" wire:model="vendedores.{{$i}}.comissao_percentual" style="width: 100%; padding: 0.375rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;" step="0.01" />
                        </td>
                        <td style="padding: 0.5rem 1rem; text-align: center;">
                            <input type="checkbox" wire:model="vendedores.{{$i}}.ativo" style="width: 1rem; height: 1rem; accent-color: #ea580c;" />
                        </td>
                        <td style="padding: 0.5rem 1rem; text-align: center;">
                            <button type="button" wire:click="removeVendedor({{$i}})" style="color: #ef4444; border: none; background: none; cursor: pointer;">
                                <x-heroicon-o-trash style="width: 1.25rem; height: 1.25rem;" />
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb; background: #fafafa;">
                <button type="button" wire:click="addVendedor" style="color: #ea580c; font-size: 0.875rem; font-weight: bold; background: white; border: 1px solid #ea580c; padding: 0.375rem 1rem; border-radius: 9999px; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                    <x-heroicon-o-plus-circle style="width: 1rem; height: 1rem;"/> ADICIONAR VENDEDOR
                </button>
            </div>
        </div>
    </div>

    <!-- Metas por Fornecedor / Estados -->
    <div class="matriz-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 0.875rem; font-weight: bold; color: #059669; text-transform: uppercase;">
                Metas por Fornecedor / Estados
            </h3>
            <button type="button" wire:click="addFornecedor" style="color: #059669; font-size: 0.875rem; font-weight: bold; background: white; border: 1px solid #059669; padding: 0.375rem 1rem; border-radius: 9999px; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                <x-heroicon-o-plus-circle style="width: 1rem; height: 1rem;"/> NOVO FORNECEDOR
            </button>
        </div>

        <div>
            @foreach($fornecedores as $i => $f)
            <div class="matriz-card">
                <div class="matriz-header">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <select wire:model="fornecedores.{{$i}}.fornecedor_id" style="font-size: 0.75rem; font-weight: bold; color: #065f46; background: #d1fae5; padding: 0.25rem 0.75rem; border-radius: 9999px; border: 1px solid #34d399; text-transform: uppercase;">
                            <option value="">SELECIONE FORNECEDOR...</option>
                            @foreach($availableFornecedores as $af)
                                <option value="{{$af['id']}}">{{$af['razao_social']}}</option>
                            @endforeach
                        </select>
                        
                        <div class="matriz-actions">
                            <select wire:model="fornecedores.{{$i}}.novoEstado" class="matriz-select">
                                <option value="">UF</option>
                                @foreach($todasUfs as $uf)
                                    <option value="{{$uf}}">{{$uf}}</option>
                                @endforeach
                            </select>
                            <button type="button" wire:click="addEstadoFornecedor({{$i}})" class="matriz-btn-add">
                                + Estado
                            </button>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="matriz-total">
                            Total Anual: R$ {{ number_format(collect($f['estados'])->flatten()->sum(), 2, ',', '.') }}
                        </div>
                        <button type="button" wire:click="removeFornecedor({{$i}})" style="color: #ef4444; border: none; background: none; cursor: pointer;" title="Excluir Card">
                            <x-heroicon-o-trash style="width: 1.25rem; height: 1.25rem;" />
                        </button>
                    </div>
                </div>
                
                <div class="matriz-scroll">
                    <table class="matriz-table">
                        <thead>
                            <tr>
                                <th class="matriz-th estado-col">Estado / Mês</th>
                                @foreach(['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'] as $mes)
                                    <th class="matriz-th">{{ $mes }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($f['estados'] as $uf => $meses)
                            <tr class="matriz-tr">
                                <td class="matriz-td estado-col">
                                    <span class="matriz-tag">{{ $uf }}</span>
                                    <button type="button" wire:click="removeEstadoFornecedor({{$i}}, '{{$uf}}')" class="matriz-remove">✖</button>
                                </td>
                                @for($m=1; $m<=12; $m++)
                                <td class="matriz-td">
                                    <input type="number" wire:model.lazy="fornecedores.{{$i}}.estados.{{$uf}}.{{$m}}" class="matriz-input" placeholder="-" />
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            
            @if(count($fornecedores) === 0)
                <div style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic; background: white; border: 1px dashed #d1d5db; border-radius: 0.5rem;">
                    Nenhum fornecedor adicionado à matriz ainda. Clique em "Novo Fornecedor".
                </div>
            @endif
        </div>
    </div>
</div>
