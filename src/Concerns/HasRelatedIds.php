<?php

namespace Yaseen\Toolset\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait HasRelatedIds
 *
 * Provides a convenient method to get a flattened list of IDs for any given relationship.
 */
trait HasRelatedIds
{
    /**
     * Get a flattened array of IDs for a given relationship.
     *
     * This method dynamically inspects the relationship and retrieves
     * the foreign keys or related keys as appropriate.
     *
     * Example:
     * $user->getRelatedIds('roles'); // Returns [1, 2, 5]
     *
     * @param string $relationName The name of the relationship method on the model.
     * @return \Illuminate\Support\Collection A collection of the related model IDs.
     * @throws \InvalidArgumentException
     */
    public function getRelatedIds(string $relationName)
    {
        if (!method_exists($this, $relationName)) {
            throw new \InvalidArgumentException("Relationship '{$relationName}' does not exist on model " . get_class($this));
        }

        $relation = $this->{$relationName}();

        // For BelongsToMany, HasMany, MorphMany, etc., the related key is on the related model.
        // The foreign key is on the current model or the pivot table.
        // We want the primary key of the related models.
        
        // Eager load the relationship with only the key we need, which is very efficient.
        $relatedKeyName = $relation->getRelated()->getQualifiedKeyName();
        $relatedModels = $relation->get([$relatedKeyName]);

        // The result is a collection of models that only have their primary key loaded.
        // The `pluck` method will then extract these keys into a simple, flat collection.
        return $relatedModels->pluck($relation->getRelated()->getKeyName());
    }

    /**
     * An accessor to get all related IDs as a key-value map.
     *
     * This is useful for serializing the model to JSON, so the client
     * can easily see which IDs are currently associated with each relation.
     *
     * To use this, add 'related_ids' to the $appends array on your model.
     *
     * @return array
     */
    public function getRelatedIdsAttribute(): array
    {
        $relatedIds = [];

        // Define a `relationsToLoadIds` property on your model to specify which relations to include.
        if (property_exists($this, 'relationsToLoadIds')) {
            foreach ($this->relationsToLoadIds as $relationName) {
                // Suffix with '_ids' for clarity in the final JSON, e.g., "roles_ids"
                $relatedIds[Str::snake($relationName) . '_ids'] = $this->getRelatedIds($relationName);
            }
        }

        return $relatedIds;
    }
}