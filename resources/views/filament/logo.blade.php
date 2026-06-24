@php
    $logo = \App\Models\Setting::where('key', 'company_logo')->first();
@endphp

@if($logo && $logo->value)
    <img src="{{ asset('storage/' . $logo->value) }}" alt="Logo da Empresa" style="height: 2.5rem; max-width: 100%;">
@else
    <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
        CRM BidFlow
    </span>
@endif
