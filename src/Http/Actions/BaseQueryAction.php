<?php
namespace Ysn\SuperCore\Http\Actions;

use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseQueryAction extends BaseAction
{
    public function authorize(Request $request) : Response {
        return Response::allow();
    }

    public abstract function handle(Builder $builder, Request $request) : JsonResponse;

    public function perform(Request $request) : JsonResponse
    {
        if($errors = $this->validate($request)){
            return self::validation($errors);
        }

        $response = $this->authorize($request);
        if ($response->denied()) {
            if($response->code() == 409){
                return self::error($response->message());
            }
            return self::unauthorized($response->message());
        }

        return $this->handle($this->buildQuery($request), $request);
    }
}
