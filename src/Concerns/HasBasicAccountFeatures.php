<?php
namespace Yaseen\Toolset\Concerns;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Yaseen\Toolset\Concerns\HasMetaColumn;
use Yaseen\Toolset\Concerns\HasConfigColumn;
use Yaseen\Toolset\Concerns\Tenantable;
use Yaseen\Toolset\Types\AccountStatus;
use Yaseen\Toolset\Types\AccountType;
use Yaseen\Toolset\Casts\Spatial\Spatialable;

trait HasBasicAccountFeatures
{
    use HasApiTokens, Authenticatable, Authorizable;
    use Tenantable;
    use HasFactory;
    use SoftDeletes;
    use Spatialable;
    use HasMetaColumn, HasConfigColumn;

    // when adding ['category_name', 'city_name' ...] don't forget to add eager relation in $with propriety
    protected $appendsFull = [
        AccountType::PERSONAL    =>  ['web_link'],
        AccountType::BUSINESS    => [
            'map_link', 'web_link', 'category_name', 'location_name'
            //'open_time', 'close_time', 'email', 'phone', 'address', 'website',
        ],
    ];
    protected $appendsList = [
        AccountType::PERSONAL        =>  [],
        AccountType::BUSINESS        => [],
    ];


    public static function bootHasBasicAccountFeatures()
    {
        static::addGlobalScope('only_active', function (Builder $builder) {
            $builder->where('status', AccountStatus::ACTIVE);
        });

        // disabled, it make problem when need some columns, ex: "->coordinates" in  making shipment
        // static::addGlobalScope('default_constraint', function (Builder $builder) {
        //     // column 'tenant_id' my be needed? or not ?
        //     // Column 'id' in field list is ambiguous => solution: use accounts.id
        //     $builder->addSelect(['accounts.id', 'accounts.tenant_id', 'uid', 'type', 'category_id', 'name', 'photo'])->withCount('active_tokens as online');
        // });
    }





    // --------------------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------- RELATIONS ----------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------------------------------



    public function scopeNoFilter($q){
        return $q->withoutGlobalScope('only_active');
    }



    public function scopeAllColumns($q){
        return $q->withoutGlobalScope('default_constraint')->addSelect('*');
    }
    public function scopeFullOptions($q){
    	return $q->withoutGlobalScope('default_constraint')->addSelect('*');
                //->withCount('active_tokens as online'); // do I need that?
    }



    public function scopePersonal($q){
    	return $q->where('type', AccountType::PERSONAL);
    }

    public function scopeBusiness($q){
    	return $q->where('type', AccountType::BUSINESS);
    }


    // --------------------------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------- RELATIONS ----------------------------------------------------------
    // --------------------------------------------------------------------------------------------------------------------------------




    // ----------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------- APPENDS & MUTATION ----------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------------------------------------------------------------------------


    public function getPhotoAttribute($photo)
    {
        return $photo ?? config('settings.images.avatar');
    }


    public function getWebLinkAttribute()
    {
        return env('WEB_DOMAIN') . '/profiles/' . $this->id . '-' . $this->name;
    }







    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ----------------------------------------------- FOR MEDIA FILES -----------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------------------------------




    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
    }

    public function uploadMedia(string $fileRequestKey)
    {
        // $this->photo = $this->addMediaFromRequest($fileRequestKey)->toMediaCollection('avatar')->getUrl();
        // $this->save();
        if($fileRequestKey=='avatar'){
            $this->photo = $this->addMediaFromRequest($fileRequestKey)->toMediaCollection('avatar')->getFullUrl();
            $this->save();
        } else {
            $this->cover = $this->addMediaFromRequest($fileRequestKey)->toMediaCollection('cover')->getFullUrl();
            $this->save();
        }
    }

    // -------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------
    // ---------------------------------------- HELPERS ------------------------------------------
    // -------------------------------------------------------------------------------------------
    // -------------------------------------------------------------------------------------------




    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => ["{$this->id}"]];
        //return ['include_external_user_ids' => $this->id];
    }

    public function routeNotificationForMail()
    {
        return $this->user->email ?? 'yassine.dabbous@gmail.com'; // to reminde me !
    }



    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale ?? 'en';
    }

}
