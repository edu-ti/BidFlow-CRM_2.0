<?php

namespace Modules\Comercial\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    public function boot(): void
    {
        parent::boot();
        
        \Modules\Comercial\Models\PropostaComercial::observe(\Modules\Comercial\Observers\PropostaComercialObserver::class);
    }
}
