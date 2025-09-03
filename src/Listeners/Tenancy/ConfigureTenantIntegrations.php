<?php
namespace Ysn\SuperCore\Listeners\Tenancy;

//use Illuminate\Support\Facades\Log;
use Tenancy\Affects\Configs\Events\ConfigureConfig;

class ConfigureTenantIntegrations
{
    /**
     * @see TenantModel $tenant
     */
    public function handle(ConfigureConfig $event)
    {
        //TenantModel
        // Log::info('-----------------------------------------------------');
        // Log::warning('changing tenant CONFIGURATION ...');
        // Log::info('-----------------------------------------------------');
        if($tenant = $event->event->tenant)
        {
            //Log::info("changing tenant CONFIGURATION for {$tenant->name} ...");
            // OVERRIDE ONESIGNAL API KEYS
            $event->set('services.onesignal.app_id', $tenant->oneSignalAppId);
            $event->set('services.onesignal.rest_api_key', $tenant->oneSignalRestApi);

            $event->set('services.twilio.sid', $tenant->twilioSid);
            $event->set('services.twilio.token', $tenant->twilioToken);
            $event->set('services.twilio.number', $tenant->twilioNumber);
            
            $event->set('services.vonage.key', $tenant->vonageKey);
            $event->set('services.vonage.secret', $tenant->vonageSecret);

            config([
                'settings' => array_merge(
                    $tenant->toArray(), 
                    config('settings', [])
                ),
            ]);
            // config([
            //     'settings.config' => array_merge(
            //         $tenant->config, 
            //         config('settings.config', [])
            //     ),
            // ]);
            
            /*
            config([
                'services' => array_merge(
                    $tenant->services, 
                    config('services', [])
                ),
            ]);
            */

            // Log::info('changing tenant CONFIGURATION done');
        }
    }
}
