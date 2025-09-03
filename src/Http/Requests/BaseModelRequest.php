<?php

namespace Yaseen\Toolset\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;

/**
 * Class BaseModelRequest
 *
 * A base FormRequest that provides automatic model loading and authorization
 * checks based on Laravel Policies.
 */
class BaseModelRequest extends FormRequest
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

    /**
     * Query the model from the database using the route key.
     */
    public function queryModel(): ?Model
    {
        $id = $this->route($this->modelRouteKey);
        return $id ? $this->modelClass::find($id) : null;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->modelClass) {
            return true; // No model class defined, so no authorization check.
        }

        $response = match ($this->getMethod()) {
            'GET' => $this->route($this->modelRouteKey)
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
}