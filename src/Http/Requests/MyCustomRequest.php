<?php
namespace Ysn\SuperCore\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Custom Validation & Authorization response
 */
class MyCustomRequest extends FormRequest
{
    use MyFailureTrait;

    public ?string $modelClass = null;
    public ?Model $model = null;
    
    public function getModel() : Model {
        $this->model ??= $this->queryModel();
        if(!$this->model){
            throw new HttpResponseException($this->notFound());
        }
        return $this->model;
    }

    public function queryModel() : ?Model {
        return $this->modelClass::find($this->route('id'));
    } 
    
    public function authorize(): bool
    {
        if(!$this->modelClass){
            return true;
        }

        $response = match ($this->getMethod()) {
            // 'GET' => !$this->route('id') ? Gate::inspect('index', $this->modelClass) : Gate::inspect('show', $this->modelClass),
            'POST' => Gate::inspect('create', $this->modelClass),
            'DELETE' => Gate::inspect('delete', $this->getModel()),
            default => Gate::inspect('update', $this->getModel()),
        };

        if ($response->denied()) {
            $this->authorizationMessage = $response->message();
            return false;
        }

        return true;
    }
}