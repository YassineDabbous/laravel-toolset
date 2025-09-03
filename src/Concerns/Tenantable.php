<?php
namespace Yaseen\Toolset\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Yaseen\Toolset\Models\Scopes\TenantableScope;
use Yaseen\Toolset\Models\TenantModel;

trait Tenantable
{
    public function tenant()
    {
        return $this->belongsTo(TenantModel::class, 'tenant_id');
    }
    
    public function scopeWithoutTenant(Builder $q)
    {
        return $q->withoutGlobalScope(new TenantableScope);
    } 

    public static function bootTenantable()
    {
        static::addGlobalScope(new TenantableScope);

        // use saving because 'creating' doesnt work with mass fillable
        static::saving(function (Model $model) {
            // checking if not defined manualy
            if (! $model->tenant_id && ! $model->relationLoaded('tenant')) {
                // //Log::debug("--------------->  bootTenantable assigning tenant id");
                // if(auth()->check() && ($tid = auth()->user()->tenant_id)){
                //     $model->tenant_id = $tid;
                // }
                // else if (isTenantIdentified()) {
                //     $model->setAttribute('tenant_id', tenantId());
                //     //$model->setRelation('tenant', tenant());
                // }
                $model->setAttribute('tenant_id', config('settings.id'));
            }
        });
    }

}
