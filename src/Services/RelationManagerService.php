<?php

namespace Yaseen\Toolset\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use \ReflectionMethod;

class RelationManagerService
{
    /**
     * The main entry point for managing a model's relationship.
     *
     * @param Model $model The parent model (e.g., a User).
     * @param string $relationName The name of the relationship (e.g., "roles").
     * @param string|null $action The generic action: "link", "unlink", or null.
     * @param array $ids The array of IDs to sync/attach/associate.
     * @param array $additionalPivotData Additional data for the intermediate table.
     * @return void
     * @throws \Exception
     */
    public function handle(
        Model $model,
        string $relationName,
        ?string $action,
        array $ids,
        array $additionalPivotData = []
    ): void {
        if (!method_exists($model, $relationName)) {
            throw new \InvalidArgumentException("Relationship '{$relationName}' does not exist on model " . get_class($model));
        }

        $relation = $model->{$relationName}();
        $relationType = (new ReflectionMethod($model, $relationName))->getReturnType()->getName();

        // --- Logic for Many-to-Many style relationships ---
        if (
            is_a($relation, BelongsToMany::class) || 
            is_a($relation, MorphToMany::class)
        ) {
            $this->handleManyToMany($relation, $action, $ids, $additionalPivotData);
            return;
        }

        // --- Logic for One-to-One / One-to-Many (inverse) ---
        if (is_a($relation, BelongsTo::class) || is_a($relation, MorphTo::class)) {
            $this->handleBelongsTo($relation, $action, $ids);
            return;
        }

        // --- Logic for One-to-One / One-to-Many ---
        if (is_a($relation, HasOne::class) || is_a($relation, HasMany::class)) {
            // ... Logic for HasOne or HasMany would go here ...
            throw new \Exception("Managing '{$relationType}' relationships is not yet supported.");
        }

        throw new \Exception("Unsupported relationship type: {$relationType}");
    }

    /**
     * Handles sync, attach, detach, toggle for Many-to-Many relationships.
     */
    protected function handleManyToMany(BelongsToMany|MorphToMany $relation, ?string $action, array $ids, array $pivotData): void
    {
        // Prepare pivot data if a simple 'additional' array was passed.
        $syncData = empty($pivotData) 
            ? $ids 
            : collect($ids)->mapWithKeys(fn($id) => [$id => $pivotData])->all();

        match ($action) {
            'link' => $relation->syncWithoutDetaching($syncData), // Safest 'link' is to add without removing others.
            'unlink' => $relation->detach($ids),
            null => $this->isToggle($ids) 
                ? $relation->toggle($syncData) // If IDs are present, toggle them.
                : $relation->sync($syncData),  // If IDs are present but not toggling, sync. If empty, sync will detach all.
            default => throw new \InvalidArgumentException("Invalid action '{$action}' for this relationship type."),
        };
    }

    /**
     * Handles associate and dissociate for BelongsTo relationships.
     */
    protected function handleBelongsTo(BelongsTo|MorphTo $relation, ?string $action, array $ids): void
    {
        // BelongsTo relationships can only have one related model.
        $id = $ids[0] ?? null;

        if ($action === 'link') {
            if ($id === null) throw new \InvalidArgumentException("An ID is required to 'link' a BelongsTo relationship.");
            $relatedModel = $relation->getRelated()->findOrFail($id);
            $relation->associate($relatedModel);
            $relation->getParent()->save();
        } elseif ($action === 'unlink') {
            $relation->dissociate();
            $relation->getParent()->save();
        } elseif ($action === null) {
            // Null action with an ID means associate, without an ID means dissociate.
            if ($id) {
                $relatedModel = $relation->getRelated()->findOrFail($id);
                $relation->associate($relatedModel);
            } else {
                $relation->dissociate();
            }
            $relation->getParent()->save();
        } else {
            throw new \InvalidArgumentException("Invalid action '{$action}' for this relationship type.");
        }
    }
    
    /**
     * A simple heuristic to determine if a 'null' action means toggle.
     * You could make this more explicit in the request payload if needed.
     */
    protected function isToggle(array $ids): bool
    {
        // For example, if you decide that a null action always means sync, return false.
        // If you want to support toggle, you might require a specific flag in the request.
        // For this example, let's say null action means 'sync'.
        return false; 
    }
}