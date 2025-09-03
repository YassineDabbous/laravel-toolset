<?php
namespace Ysn\SuperCore\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use YassineDabbous\FileCast\FileCast;
use Ysn\SuperCommon\Database\Factories\TenantModelFactory;
use Symfony\Component\Console\Input\InputInterface;
use Tenancy\Identification\Concerns\AllowsTenantIdentification;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Identification\Drivers\Http\Contracts\IdentifiesByHttp;
use Tenancy\Identification\Drivers\Console\Contracts\IdentifiesByConsole;
use Tenancy\Identification\Drivers\Queue\Contracts\IdentifiesByQueue;
use Tenancy\Identification\Drivers\Queue\Events\Processing;

class TenantModel extends Model implements Tenant, IdentifiesByHttp, IdentifiesByConsole, IdentifiesByQueue
{
    use HasFactory;
    use AllowsTenantIdentification;
    protected $connection = 'commonCnx';
    protected $table = 'tenants';
    protected $visible = ['id', 'name'];
    protected $casts = [
        'logo'            => FileCast::class,
        'config'  => 'array',
        'services'  => 'array',
        //'with_relations'  => 'array',
        //'with_count_relations'  => 'array',
    ];
    protected $attributes = [
        'config'      => '{}',
        'services'    => '{}',
    ];



    protected $appends = [
        'logo_url' ,
        // // services
        // 'onesignal_app_id' , 'onesignal_rest_api',
        // 'twilio_sid' , 'twilio_token', 'twilio_number',
        // 'vonage_key' , 'vonage_secret', 
        // // config
        // 'accounts_approving', 'accounts_max_business', 'accounts_visits_notifications',

        // 'posts_approving', 'posts_scheduling', 'posts_expiring', 'posts_as_anonymous',
        // 'posts_min_media', 'posts_feed_exclude',

        // 'comments_approving', 'comments_as_anonymous',

        // 'reviews_approving',

        // 'searchs_matching_notifications',

        // 'enable_auto_update', 'auto_update_date', 'treatment_delay',
        //  'amount_capital_delivery', 'amount_provinces_delivery', 'amount_delivery_man', 
        // 'fee_additional_money', 'fee_additional_weight', 'fee_remote_areas',
        // 'xxxxxx', 'xxxxxx', 'xxxxxx', 'xxxxxx',
        
        'auto_update_enabled', 'auto_update_date', 'treatment_delay',
         'default_driver_amount', 'default_delivery_price', 
        'additional_money_fee', 'additional_money_threshold', 'additional_weight_fee', 'additional_weight_threshold', 
    ];


    public function publishSecrets(){
        $this->visible = [];
        $this->hidden = ['config', 'services'];
    }


    protected static function newFactory()
    {
        return TenantModelFactory::new();
    }


    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }





    public function tenantIdentificationByHttp(Request $request): ?Tenant
    {
        //dump('----------------- Request tenantable false ');
        if(config('qarya.tenantable') == false){
            TenantModel::unguard();
            return new TenantModel(config('settings'));
        }

        if( $request->hasHeader('Tenant-Id') ){
            return $this->newQuery()->where('id', $request->header('Tenant-Id'))->first();
        }
        //return null;
        list($subdomain) = explode('.', $request->getHost(), 2);
        $t = $this->query()
            ->where('subdomain', $subdomain)
            ->first();
        //Log::info($t);
        return $t;// ?? $this->query()->first();
    }




    public function tenantIdentificationByQueue(Processing $event): ?Tenant
    {
        if ($event->tenant) {
            return $event->tenant;
        }
        // if(config('qarya.tenantable') == false){
        //     TenantModel::unguard();
        //     return new TenantModel(config('settings'));
        // }
        if ($event->tenant_key && $event->tenant_identifier === $this->getTenantIdentifier()) {
            return $this->newQuery()->where($this->getTenantKeyName(), $event->tenant_key)->first();
        }
        return null;
    }





    public function tenantIdentificationByConsole(InputInterface $input): ?Tenant
    {
        if(config('qarya.tenantable') == false){
            TenantModel::unguard();
            return new TenantModel(config('settings'));
        }
        if ($input->hasParameterOption('--tenant-identifier')) {
            // if($input->getParameterOption('--tenant-identifier') != $this->getTenantIdentifier()) {
            //     return null;
            // }
            return $this->newQuery()->where($this->getTenantKeyName(), $input->getParameterOption('--tenant-identifier'))->first();
        }
        if ($input->hasParameterOption('--tenant')) {
            return $this->query()
                ->where('slug', $input->getParameterOption('--tenant'))
                ->first();
        }

        return null;// ?? $this->query()->first();
    }


	public function getLogoUrlAttribute(){
		return relativeToFullUrl($this->logo);
	}


    //
    //
    //
    // services
    //
    //
    //

    /**
     * @return string|null
     */
    public function getOneSignalAppIdAttribute()
    {
        return $this->services['onesignal_app_id'] ?? null;
    }
    /**
     * @return string|null
     */
    public function getOneSignalRestApiAttribute()
    {
        return $this->services['onesignal_rest_api'] ?? null;
    }

     
    public function twilioSid() : Attribute
    {
        return Attribute::make(get: fn() => $this->services['twilio_sid'] ?? null);
    }
    public function twilioToken() : Attribute
    {
        return Attribute::make(get: fn() => $this->services['twilio_token'] ?? null);
    }
    public function twilioNumber() : Attribute
    {
        return Attribute::make(get: fn() => $this->services['twilio_number'] ?? null);
    }
 
    
    public function vonageKey() : Attribute
    {
        return Attribute::make(get: fn() => $this->services['vonage_key'] ?? null);
    }
    public function vonageSecret() : Attribute
    {
        return Attribute::make(get: fn() => $this->services['vonage_secret'] ?? null);
    }


    //
    //
    //
    // configurations
    //
    //
    //


    public function getAccountsApprovingAttribute() : bool
    {
        return $this->config['accounts_approving'] ?? false;
    }
    public function getAccountsMaxBusinessAttribute() : int
    {
        return $this->config['accounts_max_business'] ?? 0;
    }
    public function getAccountsVisitsNotificationsAttribute() : bool
    {
        return $this->config['accounts_visits_notifications'] ?? false;
    }



    public function getPostsApprovingAttribute() : bool
    {
        return $this->config['posts_approving'] ?? false;
    }
    public function getPostsSchedulingAttribute() : bool
    {
        return $this->config['posts_scheduling'] ?? false;
    }
    public function getPostsExpiringAttribute() : bool
    {
        return $this->config['posts_expiring'] ?? false;
    }
    public function getPostsAsAnonymousAttribute() : bool
    {
        return $this->config['posts_as_anonymous'] ?? false;
    }

    public function getPostsMinMediaAttribute() : int
    {
        return $this->config['posts_min_media'] ?? 0;
    }

    public function getPostsFeedExcludeAttribute() : array
    {
        return $this->config['posts_feed_exclude'] ?? [];
    }





    public function getCommentsApprovingAttribute() : bool
    {
        return $this->config['comments_approving'] ?? false;
    }
    public function getCommentsAsAnonymousAttribute() : bool
    {
        return $this->config['comments_as_anonymous'] ?? false;
    }


    public function getReviewsApprovingAttribute() : bool
    {
        return $this->config['reviews_approving'] ?? false;
    }


    public function getSearchsMatchingNotificationsAttribute() : bool
    {
        return $this->config['searchs_matching_notifications'] ?? false;
    }





    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    // DELIVERY
        // 'auto_update_enabled', 'auto_update_date', 'treatment_delay',
        //  'default_driver_amount', 'default_delivery_price', 
        // 'additional_money_fee', 'additional_money_threshold', 'additional_weight_fee', 'additional_weight_threshold', 
        // 'amount_capital_delivery', 'amount_provinces_delivery', 'amount_delivery_man', 
    
    public function getAutoUpdateEnabledAttribute() : bool {
        if($this->config['auto_update_enabled'] == 'false'){
            return false;
        }
        return $this->config['auto_update_enabled'] ?? false;
    }
    public function getAutoUpdateDateAttribute() : ?int {return $this->config['auto_update_date'] ?? null;}
    public function getTreatmentDelayAttribute() : ?int {return $this->config['treatment_delay'] ?? null;}
    public function getDefaultDriverAmountAttribute() : int {return $this->config['default_driver_amount'] ?? 0;}
    public function getDefaultDeliveryPriceAttribute() : int {return $this->config['default_delivery_price'] ?? 0;}
    public function getAdditionalMoneyFeeAttribute() : int {return $this->config['additional_money_fee'] ?? 0;}
    public function getAdditionalMoneyThresholdAttribute() : int {return $this->config['additional_money_threshold'] ?? 0;}
    public function getAdditionalWeightFeeAttribute() : int {return $this->config['additional_weight_fee'] ?? 0;}
    public function getAdditionalWeightThresholdAttribute() : int {return $this->config['additional_weight_threshold'] ?? 0;}

}
