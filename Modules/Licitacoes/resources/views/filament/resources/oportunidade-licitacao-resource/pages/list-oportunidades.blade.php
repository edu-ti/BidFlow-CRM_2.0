<x-filament-panels::page>
    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        
        <!-- Sidebar Filtros -->
        <div style="flex: 1; min-width: 250px; max-width: 300px; background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; align-self: flex-start; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <a href="#" wire:click.prevent="resetFilters" style="color: #2563eb; font-size: 0.875rem; text-decoration: none;">Limpar</a>
                <a href="#" wire:click.prevent="$refresh" style="color: #2563eb; font-size: 0.875rem; text-decoration: none;">Recarregar</a>
            </div>
            
            <form wire:submit.prevent="$refresh">
                <!-- Objeto -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Objeto</label>
                    <input type="text" wire:model.defer="searchObjeto" placeholder="Pesquise por Objeto" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.875rem;" />
                    <div style="margin-top: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" wire:model.defer="buscaExata" id="buscaExata" />
                        <label for="buscaExata" style="font-size: 0.75rem; color: #4b5563;">Busca Exata</label>
                    </div>
                </div>
                
                <!-- Filtrar por -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Filtrar por:</label>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #4b5563;">
                        <label><input type="radio" name="filtrar_por" checked /> Estado</label>
                        <label><input type="radio" name="filtrar_por" /> Região</label>
                        <label><input type="radio" name="filtrar_por" /> Raio de atuação</label>
                    </div>
                </div>
                
                <!-- Estado -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Estado</label>
                    <select wire:model.defer="estado" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.875rem; background: white;">
                        <option value="">Selecione os Estados</option>
                        <option value="SP">São Paulo</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PR">Paraná</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="DF">Distrito Federal</option>
                    </select>
                </div>
                
                <!-- Cidade -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Cidade</label>
                    <input type="text" wire:model.defer="cidade" placeholder="Selecione as Cidades" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.875rem;" />
                </div>
                
                <!-- Nº Edital -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Nº Edital</label>
                    <input type="text" wire:model.defer="numeroEdital" placeholder="Pesquise por Nº Edital" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.875rem;" />
                </div>
                
                <!-- Modalidades -->
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Modalidades</label>
                    <select wire:model.defer="modalidade" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem; font-size: 0.875rem; background: white;">
                        <option value="">Selecione as Modalidades</option>
                        <option value="Pregão Eletrônico">Pregão Eletrônico</option>
                        <option value="Pregão Presencial">Pregão Presencial</option>
                        <option value="Concorrência">Concorrência</option>
                        <option value="Dispensa">Dispensa</option>
                    </select>
                </div>
                
                <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 1rem 0;" />
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span style="font-size: 0.875rem; color: #374151;">Salvar pesquisa?</span>
                    <div style="width: 2.5rem; height: 1.25rem; background: #e5e7eb; border-radius: 9999px;"></div>
                </div>
                
                <x-filament::button type="submit" size="md" color="primary" style="width: 100%;">Pesquisar</x-filament::button>
            </form>
        </div>
        
        <!-- Área de Resultados -->
        <div style="flex: 3; min-width: 400px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; background: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                    <span>Mostrar</span>
                    <select wire:model.live="perPage" style="border: 1px solid #d1d5db; border-radius: 0.25rem; padding: 0.25rem;">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div style="font-size: 0.875rem; color: #6b7280; font-weight: bold;">
                    Total de {{ $this->oportunidades->total() }} licitações.
                </div>
            </div>
            
            <div>
                @forelse($this->oportunidades as $index => $licitacao)
                    @include('licitacoes::components.licitacao-card', ['licitacao' => $licitacao, 'index' => $this->oportunidades->firstItem() + $index])
                @empty
                    <div style="text-align: center; color: #6b7280; padding: 3rem; background: white; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                        Nenhuma licitação encontrada com os filtros atuais.
                    </div>
                @endforelse
            </div>
            
            <div style="margin-top: 1rem;">
                {{ $this->oportunidades->links() }}
            </div>
        </div>
        
    </div>
</x-filament-panels::page>
