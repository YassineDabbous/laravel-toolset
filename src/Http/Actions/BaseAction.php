<?php
namespace Ysn\SuperCore\Http\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Ysn\SuperCore\Http\Responses\HasRestfulResponse;
use Ysn\SuperCore\Repositories\HasQueryHerlpers; 

abstract class BaseAction
{
    use HasRestfulResponse;
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



//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

// namespace Ysn\SuperCore\Http\Actions;

// use Illuminate\Auth\Access\Response;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\Relation;
// use Illuminate\Http\JsonResponse;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
// use Ysn\SuperCore\Http\Responses\HasRestfulResponse;
// use Ysn\SuperCore\Repositories\HasQueryHerlpers; 

// abstract class BaseAction
// {
//     use HasRestfulResponse;
//     use HasQueryHerlpers;
    
//     public Request $filter;
//     public Request $payload;

//     public function filterRules() : array{
//         return [];
//     }
//     public function filterMessages() : array{
//         return [];
//     }

//     public function payloadRules() : array{
//         return [];
//     }
//     public function payloadMessages() : array{
//         return [];
//     }

//     public function clauses() : array{
//         return [];
//     }
//     public function authorize(?Model $model) : Response {
//         return Response::allow();
//     }

//     public abstract function perform(Request $request) : JsonResponse;
    // public function perform(Request $request) : JsonResponse
    // {
    //     if($request->filled('filter')){
    //         $this->filter = new Request($request->filter);
    //         if($errors = $this->validateFilter()){
    //             return self::validation($errors);
    //         }
    //     }
    //     if($request->filled('payload')){
    //         $this->payload = new Request($request->payload);
    //         if($errors = $this->validatePayload()){
    //             return self::validation($errors);
    //         }
    //     }
    // }

//     public function validateFilter()
//     {
//         if($this->filter){
//             $validator = Validator::make($this->filter->all(), $this->filterRules(), $this->filterMessages());
     
//             if ($validator->fails()) {
//                 return $validator->errors();
//             }
//         }
//     }
//     public function validatePayload()
//     {
//         if($this->payload){
//             $validator = Validator::make($this->payload->all(), $this->payloadRules(), $this->payloadMessages());
     
//             if ($validator->fails()) {
//                 return $validator->errors();
//             }
//         }
//     }

//     public function buildQuery(Request $request) : Builder
//     {
//         $model = Relation::getMorphedModel($request->type);
//         $q = $model::query();
//         if($request->filled('keys')){
//             $q->whereIn('id', $request->keys);
//         }
//         if($this->filter){
//             $filters = $this->clauses();
//             foreach ($filters as $key => $value) {
//                 if($this->filter->filled($key)){
//                     $value($q);
//                 }
//             }
//         }


//         return $q;
//     }

// }










