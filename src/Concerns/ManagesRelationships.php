<?php

namespace Yaseen\Toolset\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Yaseen\Toolset\Http\Responses\FormattedApiResponses;
use Yaseen\Toolset\Services\RelationManagerService; 

/**
 * Trait ManagesRelationships
 *
 * Provides a standardized way to manage relationships on Eloquent models via API requests.
 */
trait ManagesRelationships
{
    use FormattedApiResponses;

    /**
     * Handle managing multiple relationships on a model in a single request.
     *
     * @param int $id The ID of the parent model.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function manageRelations($id, Request $request)
    {
        // Assuming the controller has a `modelClass` property
        if (!property_exists($this, 'modelClass')) {
            return $this->error('Controller must have a modelClass property.', 500);
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'action' => ['nullable', 'string', 'in:link,unlink'],
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
            'additional' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return $this->validation($validator->messages());
        }

        $model = ($this->modelClass)::find($id);

        if(!$model){
            return $this->notFound();
        }

        Gate::authorize('update', $this->modelClass);

        $validated = $validator->validated();

        try {
            $this->manageRelationFor(
                $model,
                $validated['name'],
                $validated['action'] ?? null,
                $validated['ids'],
                $validated['additional'] ?? []
            );
        } catch (\InvalidArgumentException | \BadMethodCallException $e) {
            return $this->error($e->getMessage(), 400);
        } catch (\Exception $e) {
            \Log::error('Relationship management failed', ['error' => $e]);
            return $this->error('An unexpected error occurred: ' . $e->getMessage(), 500);
        }

        return $this->success(null, "Relationship '{$validated['name']}' updated successfully.");
    }

    /* Manage a specific relationship on a model. 
     *
     * @param Model $model The parent model.
     * @param string $relationName The name of the relationship.
     * @param string|null $action The action to perform: "link", "unlink", or null.
     * @param array $ids The IDs to sync/attach/associate.
     * @param array $additionalPivotData Additional data for pivot tables.
     * @return void
     */
    public function manageRelationFor(
        Model $model,
        string $relationName,
        ?string $action,
        array $ids,
        array $additionalPivotData = []
    ){
        $relationManager = resolve(RelationManagerService::class);
        $relationManager->handle(
            $model,
            $relationName,
            $action,
            $ids,
            $additionalPivotData
        );
    }
}