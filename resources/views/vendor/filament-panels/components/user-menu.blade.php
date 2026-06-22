@props([
    'position' => null,
])

@php
    use Filament\Actions\Action;
    use Filament\Enums\UserMenuPosition;
    use Illuminate\Support\Arr;

    $user = filament()->auth()->user();

    $position ??= filament()->getUserMenuPosition();

    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
@endphp

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_BEFORE) }}

<x-filament::dropdown
    :placement="($position === UserMenuPosition::Topbar) ? 'bottom-end' : 'top-end'"
    :teleport="$position === UserMenuPosition::Topbar"
    :attributes="
        \Filament\Support\prepare_inherited_attributes($attributes)
            ->class(['fi-user-menu'])
    "
>
    <x-slot name="trigger">
        @if ($position === UserMenuPosition::Topbar)
            <button
                aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger"
            >
                <x-filament-panels::avatar.user :user="$user" loading="lazy" />
            </button>
        @else
            <button
                aria-label="{{ __('filament-panels::layout.actions.open_user_menu.label') }}"
                type="button"
                class="fi-user-menu-trigger"
            >
                <x-filament-panels::avatar.user :user="$user" loading="lazy" />

                <span
                    @if ($isSidebarCollapsibleOnDesktop)
                        x-show="$store.sidebar.isOpen"
                    @endif
                    class="fi-user-menu-trigger-text"
                >
                    {{ filament()->getUserName($user) }}
                </span>

                {{
                    \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::ChevronUp, alias: \Filament\View\PanelsIconAlias::USER_MENU_TOGGLE_BUTTON, attributes: new \Illuminate\View\ComponentAttributeBag([
                        'x-show' => $isSidebarCollapsibleOnDesktop ? '$store.sidebar.isOpen' : null,
                    ]))
                }}
            </button>
        @endif
    </x-slot>

    <!-- Header Customizado Mocado -->
    <x-filament::dropdown.header color="gray" icon="heroicon-o-user-circle">
        {{ filament()->getUserName($user) }}
    </x-filament::dropdown.header>

    @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()))
        <x-filament::dropdown.list>
            <x-filament-panels::theme-switcher />
        </x-filament::dropdown.list>
    @endif

    <x-filament::dropdown.list>
        <!-- Minha Conta -->
        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; padding: 8px 12px; letter-spacing: 0.05em; margin-top: 4px;">
            MINHA CONTA
        </div>
        
        <x-filament::dropdown.list.item icon="heroicon-o-face-smile" href="/admin/preferencias-pessoais" tag="a" title="Preferências pessoais">
            Preferências pessoais
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item icon="heroicon-o-calendar" href="/admin/sincronizacao-calendario" tag="a" title="Sincronização de calendário">
            Sincronização de calendário
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>

    <x-filament::dropdown.list>
        <!-- Visão Geral da Empresa -->
        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; padding: 8px 12px; letter-spacing: 0.05em; margin-top: 4px;">
            VISÃO GERAL DA EMPRESA
        </div>
        
        <x-filament::dropdown.list.item icon="heroicon-o-cog-8-tooth" href="#" tag="a" title="Configurações da empresa">
            Configurações da empresa
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item icon="heroicon-o-users" href="#" tag="a" title="Gerenciar usuários">
            Gerenciar usuários
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item icon="heroicon-o-chart-bar" href="#" tag="a" title="Visão geral do usuário">
            Visão geral do usuário
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>

    <x-filament::dropdown.list>
        <!-- Logout -->
        <form action="{{ filament()->getLogoutUrl() }}" method="post">
            @csrf
            <x-filament::dropdown.list.item icon="heroicon-o-arrow-left-on-rectangle" tag="button" type="submit">
                Sair
            </x-filament::dropdown.list.item>
        </form>
    </x-filament::dropdown.list>
</x-filament::dropdown>

{{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::USER_MENU_AFTER) }}
