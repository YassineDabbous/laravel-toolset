<?php
namespace Ysn\SuperCore\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use YassineDabbous\DynamicQuery\HasDynamicFields;
use Ysn\SuperCore\Casts\Spatial\LocationCast;
use Ysn\SuperCore\Concerns\HasBasicAccountFeatures;
use Ysn\SuperSet\Shipment\Models\ShipmentModel;

class BaseAccount extends Model implements AuthenticatableContract , HasMedia, HasLocalePreference, AuthorizableContract
{
    use HasBasicAccountFeatures, InteractsWithMedia {
        HasBasicAccountFeatures::registerMediaCollections insteadof InteractsWithMedia;
    }

    //public $guard_name = '*'; // laravel-permission package
    protected $connection = 'authCnx';
    protected $table = 'accounts';
    protected $fillable = ['rating'];
    protected $casts = [
        'rating'    => 'string',
        'config'      => 'array',
        'meta'      => 'array',
        'extra'     => 'array',
        'online'    => 'boolean',
        'followed'  => 'boolean',
        'coordinates' => LocationCast::class,
    ];
    protected $attributes = [
        'config'      => '{}',
        'meta'      => '{}',
        'extra'     => '{}',
    ];


    protected $hidden = [
        'guest_device',
        'password',
        'remember_token',
    ];

    
    const configKeys = [
        // 'is_anonymous'
    ];
    const configRules = [
        // 'is_anonymous'   => ['boolean'],
    ];

  //
  //
  //
  //
  //
  //
  // HasDynamicFields
  //
  //
  use HasDynamicFields;
    public function dynamicColumns() { 
        return [
            'id', 'uid', 'name', 'phone', 'status', 'type', 'sub_type', 'tenant_id', 'owner_id', 'creator_id', 'deleter_id', 
            'category_id', 'country_id', 'location_id', 'branch_id', 'repository_id',
            'salary', 'name', 'summary', 'phone', 'email', 'photo', 'coordinates',
            'rating', 'social_weight', 'chat_privacy', 'online_status', 'extra', 'config', 'meta', 
            'is_deleted', 'created_at','updated_at','deleted_at',
        ];
    }
    public function dynamicRelations(){
        return [
            'driver_shipments'
            // 'city', 'town', 'branch', 'repository', 
        ];
    }
    public function dynamicAppends() { 
        return [
            'manifest_url' => 'id',
            // 'city_name' => 'city',
            // 'branch_name' => 'branch',
            // 'repository_name' => 'repository',
        ];
    } 
    public function dynamicAggregates(){
        return [
            'driver_shipments_count' => fn($q) => $q->withCount(['driver_shipments']), // , fn($b) => $b->where('driver_id', $this->id)
        ];
    }

    
    public function driver_shipments(){
        return $this->hasMany(ShipmentModel::class, 'driver_id');
    }
    
    public function getManifestUrlAttribute()
    {
        return env('WEB_DOMAIN') . '/shipments/manifest/' . $this->id  ;
    }

}
