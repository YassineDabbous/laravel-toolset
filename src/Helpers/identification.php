<?php

use Ysn\SuperCore\Models\Account;
use Ysn\SuperCore\Models\TenantModel;
use Tenancy\Environment;
use Tenancy\Identification\Contracts\Tenant;

if (! function_exists('tenancy')) {
    /**
     * @return Environment
     */
    function tenancy()
    {
        return app(Environment::class);
    }
}


if (! function_exists('tenant')) {
    /**
     * @return Tenant|TenantModel
     */
    function tenant() : Tenant
    {
        return app(Environment::class)->getTenant();
    }
}

if (! function_exists('isTenantIdentified')) {
    /**
     * @return boolean
     */
    function isTenantIdentified() : bool
    {
        if(!config('qarya.tenantable')){
            return false;
        }
        $t = app(Environment::class);
        return $t->isIdentified() && ($t->getTenant() != null);
    }
}


if (! function_exists('tenantId')) {
    /**
     * @return int
     */
    function tenantId() : int
    {
        return app(Environment::class)->getTenant()->getTenantKey(); //->id
        //return request()->header('tenant_id', '0');
    }
}


if (! function_exists('userId')) {
    /**
     * @return int
     */
    function userId()
    {
        return auth()->user()->uid;
        //$user = auth()->user()->user;
        //return $user ? $user->id : null;
    }
}

if (! function_exists('accountId')) {
    /**
     * @return int
     */
    function accountId()
    {
        return auth()->id();
    }
}




if (! function_exists('user')) {
    /**
     * Get the available auth instance.
     *
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function user()
    {
        return auth()->user()->user;
    }
}


if (! function_exists('account')) {
    function account()  : Account // | Illuminate\Contracts\Auth\Authenticatable
    {
        return auth()->user();
    }
}





if (! function_exists('accountClass')) {
    /**
     * Get qarya account model class.
     */
    function accountClass() : string
    {
        return config('qarya.account');
    }
}
