<?php

namespace Yaseen\Toolset\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

/**
 * Class BaseFormRequest 
 *
 * A base FormRequest that provides automatic model loading and authorization
 * checks based on Laravel Policies.
 */
class BaseFormRequest extends FormRequest
{
    use HasStandardizedErrors;

    /**
     * The Eloquent model class this request is for.
     * Must be set in the child class.
     * e.g., protected ?string $modelClass = User::class;
     */
    public ?string $modelClass = null;

    /**
     * The route parameter name used to find the model.
     * Can be overridden in the child class.
     */
    protected string $modelRouteKey = 'id';

    /**
     * The resolved model instance.
     */
    public ?Model $model = null;

    /**
     * Get the resolved and authorized model instance.
     * Throws a 404 HttpResponseException if the model is not found.
     */
    public function getModel(): Model
    {
        if (!$this->model) {
            $this->model = $this->queryModel();
            if (!$this->model) {
                throw new HttpResponseException($this->notFound());
            }
        }
        return $this->model;
    }

    public function getId(): mixed
    {
        return $this->route($this->modelRouteKey);
    }

    /**
     * Query and return the model instance based on the route parameter.
     * Override this method in the child class to customize model retrieval.
     */
    public function queryModel(): ?Model
    {
        $id = $this->route($this->modelRouteKey);
        return $id ? $this->modelClass::find($id) : null;
    }

    /** Determine if the user is authorized to make this request. */
    public function authorize(): bool
    {
        if (!$this->modelClass) {
            return true; // No model class defined, so no authorization check.
        }

        $response = match ($this->getMethod()) {
            'GET' => $this->getId()
                ? Gate::inspect('view', $this->getModel())      // Show single resource
                : Gate::inspect('viewAny', $this->modelClass),  // Index/list resources
            'POST' => Gate::inspect('create', $this->modelClass),
            'PUT', 'PATCH' => Gate::inspect('update', $this->getModel()),
            'DELETE' => Gate::inspect('delete', $this->getModel()),
            default => Response::allow(), // Allow other methods by default.
        };

        if ($response->denied()) {
            $this->authorizationMessage = $response->message();
            return false;
        }

        return true;
    }


    /** Specify keys that should be treated as boolean values */
    public function booleanKeys(): array
    {
        return [];
    }

    protected function prepareForValidation()
    {
        parent::prepareForValidation();
        
        // Convert specified keys to boolean values, eg 'true', 'false', '1', '0', etc.
        foreach ($this->booleanKeys() as $key) {
            if ($this->has($key)) {
                $this->merge([
                    $key => filter_var($this->input($key), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                ]);
            }
        }
    }
    
    /** Define validation rules for different HTTP methods. */
    public function rules(): array
    {
        return match ($this->getMethod()) {
            'GET' => $this->searchRules(),
            'POST' => $this->createRules(),
            'PUT', 'PATCH' => $this->updateRules(),
            'DELETE' => [],
            default => [],
        };
    }

    /** Define validation rules for searching (GET requests). */
    public function searchRules(): array
    {
        return [];
    }

    /** Define validation rules for creating (POST requests). */
    public function createRules(): array
    {
        return [];
    }

    /** Define validation rules for updating (PUT/PATCH requests). */
    public function updateRules(): array
    {
        return [];
    }
}