<?php

namespace Ysn\SuperCore\Providers;

use Tenancy\Affects\Configs\Events\ConfigureConfig;
use Tenancy\Identification\Events\NothingIdentified;
use Ysn\SuperCore\Listeners\Tenancy\ConfigureTenantIntegrations;
use Ysn\SuperCore\Listeners\Tenancy\NoTenantIdentified;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class TenancyEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NothingIdentified::class => [
           NoTenantIdentified::class,
        ],
        ConfigureConfig::class => [
            ConfigureTenantIntegrations::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}