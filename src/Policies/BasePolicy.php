<?php


namespace Yaseen\Toolset\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User;

class BasePolicy
{
    use HandlesAuthorization;
    
    public function permission($policy) : String
    {
        return static::class.'_'.$policy;
    }

    public function viewAnyForAdmin($user) : Response|bool 
    {
        if( ! $user->can($this->permission(__FUNCTION__ )) ) {
            return Response::deny('you are not autorized');
        }
        return Response::allow();
    }

    // By adding `?User`, we tell Laravel that guests (where $user is null) are allowed.
    public function viewAny(?User $user) : Response|bool
    {
        return $user?->can($this->permission(__FUNCTION__ )) ?? Response::deny('you are not autorized');
    }

    // By adding `?User`, we tell Laravel that guests (where $user is null) are allowed.
    public function view(?User $user, $model) : Response|bool
    {
        return $user?->can($this->permission(__FUNCTION__ )) ?? Response::deny('you are not autorized');
    }

    public function create($user) : Response|bool
    {
        return $user->can($this->permission(__FUNCTION__ ));
    }

    public function update($user, $model) : Response|bool
    {
        return $user->can($this->permission(__FUNCTION__ ));
    }

    public function delete($user, $model) : Response|bool
    {
        return $user->can($this->permission(__FUNCTION__ ));
    }

}
