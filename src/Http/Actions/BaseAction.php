<?php
namespace Yaseen\Toolset\Http\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yaseen\Toolset\Http\Responses\FormattedApiResponses;
use Yaseen\Toolset\Repositories\HasQueryHerlpers; 

abstract class BaseAction
{
    use FormattedApiResponses;
    use HasQueryHerlpers;

    public function rules(Request $request) : array{
        return [];
    }
    public function messages(Request $request) : array{
        return [];
    }
    public function clauses(Request $request) : array{
        // ex:
        //     return [
        //         'store_id' => fn(Builder $q) => $q->where('store_id', $request->store_id),
        //         'statuses' => fn(Builder $q) => $q->whereIn('status_id', $request->statuses),
        //     ];
        return [];
    }

    public abstract function perform(Request $request) : JsonResponse;
    

    public function validate(Request $request)
    {
        if($request){
            $validator = Validator::make($request->all(), $this->rules($request), $this->messages($request));

            if ($validator->fails()) {
                return $validator->errors();
            }
        }
    }

    public function buildQuery(Request $request) : Builder
    {
        $model = Relation::getMorphedModel($request->_type_);
        $q = $model::query();
        if($request->filled('_keys_') && count($request->_keys_) > 0 ){
            $q->whereIn('id', $request->_keys_);
        }
        // if($request->filled('filter')){
        
        // }

        $filters = $this->clauses($request);
        foreach ($filters as $key => $value) {
            if($request->filled($key)){
                $value($q);
            }
        }

        return $q;
    }
}