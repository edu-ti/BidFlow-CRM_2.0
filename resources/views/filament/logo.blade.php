@php
    $logo = \App\Models\Setting::where('key', 'company_logo')->first();
@endphp

@if($logo && $logo->value && \Illuminate\Support\Facades\Storage::disk('public')->exists($logo->value))
    <img src="{{ \Illuminate\Support\Facades\Storage::url($logo->value) }}" alt="Logo da Empresa" style="height: 2.5rem; max-width: 100%;">
@else
    <span class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">
        CRM BidFlow
    </span>
@endif
