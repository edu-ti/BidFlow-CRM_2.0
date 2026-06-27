@php
    $logo = \App\Models\Setting::where('key', 'company_logo')->first();
    $logoDark = \App\Models\Setting::where('key', 'company_logo_dark')->first();
@endphp

<style>
    html.dark .fi-logo-light { display: none !important; }
    html:not(.dark) .fi-logo-dark { display: none !important; }
</style>

@if($logo && $logo->value && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo->value))
    <!-- Logo Claro -->
    <img src="{{ \Illuminate\Support\Facades\Storage::url($logo->value) }}" alt="Logo da Empresa" class="fi-logo-light" style="height: 2.5rem; max-width: 100%;">
    
    @if($logoDark && $logoDark->value && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoDark->value))
        <!-- Logo Escuro -->
        <img src="{{ \Illuminate\Support\Facades\Storage::url($logoDark->value) }}" alt="Logo da Empresa" class="fi-logo-dark" style="height: 2.5rem; max-width: 100%;">
    @else
        <!-- Fallback Logo Escuro -->
        <img src="{{ \Illuminate\Support\Facades\Storage::url($logo->value) }}" alt="Logo da Empresa" class="fi-logo-dark" style="height: 2.5rem; max-width: 100%;">
    @endif
@else
    <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
        CRM BidFlow
    </span>
@endif
