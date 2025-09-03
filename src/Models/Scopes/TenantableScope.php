<?php

namespace Ysn\SuperCore\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantableScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if( config('settings.id') ){
            $builder->where($model->getTable().'.tenant_id', config('settings.id')); // tenantId()
        }
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withoutTenancy', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
