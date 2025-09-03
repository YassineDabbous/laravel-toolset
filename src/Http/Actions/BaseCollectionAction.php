<?php
namespace Ysn\SuperCore\Http\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Response; 
use Illuminate\Database\Eloquent\Model;

abstract class BaseCollectionAction extends BaseAction
{
    public abstract function handle(Collection $collection, Request $request) : JsonResponse;
    
    public function authorize(?Model $model, Request $request) : Response {
        return Response::allow();
    }

    public function perform(Request $request) : JsonResponse
    {
        // \Log::debug($request->all());
        if($request->filled('_keys_') && (!is_array($request->_keys_) || count($request->_keys_)==0)){
            throw new \Exception('Invalid action request, no items selected !');
        }
        if($errors = $this->validate($request)){
            return self::validation($errors);
        }
        $models = $this->buildQuery($request)->get();
        foreach ($models as $model) {
            $response = $this->authorize($model, $request);
            if ($response->denied()) {
                if($response->code() == 409){
                    return self::error($response->message());
                }
                return self::unauthorized($response->message());
            }
        }
        return $this->handle($models, $request);
    }
}
