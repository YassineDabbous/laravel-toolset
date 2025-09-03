<?php

namespace Ysn\SuperCore\Models\Scopes;

use Ysn\SuperCore\Types\TenantType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantableAccountRelationScope implements Scope
{
    protected $withRelations = [
        TenantType::NEWS         => ['category', 'locations', ],
        TenantType::FREELANCE    => ['category', 'appeals', ],
    ];

    protected $withRelationsCount = [
    ];


    public function apply(Builder $builder, Model $model)
    {
        if( config('settings.type') ){
            if(isset($this->withRelations[ config('settings.type') ])){
                $builder->with($this->withRelations[ config('settings.type') ]);
            }
            if(isset($this->withRelationsCount[ config('settings.type') ])){
                $builder->withCount($this->withRelationsCount[ config('settings.type') ]);
            }
        }
    }


    public function extend(Builder $builder)
    {
        $builder->macro('withoutTenancyRelations', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
