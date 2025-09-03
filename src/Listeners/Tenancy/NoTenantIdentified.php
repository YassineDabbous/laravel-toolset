<?php
namespace Ysn\SuperCore\Listeners\Tenancy;

//use Illuminate\Support\Facades\Log;
use Tenancy\Identification\Events\NothingIdentified;

class NoTenantIdentified
{
    public function handle(NothingIdentified $event)
    {
        // Log::error('-----------------------------------------------------');
        // Log::error('Tenant NOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOT identified');
        // Log::error('-----------------------------------------------------');
        //abort(404);
    }
}
