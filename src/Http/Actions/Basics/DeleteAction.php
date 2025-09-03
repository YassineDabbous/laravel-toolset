<?php
namespace Ysn\SuperCore\Http\Actions\Basics;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Ysn\SuperCore\Http\Actions\BaseCollectionAction; 

class DeleteAction extends BaseCollectionAction
{
    public function authorize(?Model $model, Request $request) : Response {
        return Gate::inspect('delete', $model);
    }

    public function handle(Collection $collection, Request $request) : JsonResponse {
        foreach ($collection as $model) {
            // $model->delete();
        }
        return $this->success();
    }
}