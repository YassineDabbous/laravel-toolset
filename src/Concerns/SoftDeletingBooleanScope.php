<?php

namespace Yaseen\Toolset\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SoftDeletingBooleanScope extends SoftDeletingScope
{

	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param Model&SoftDeletesBoolean $model
	 */
	public function apply(Builder $builder, Model $model): void
	{
		$builder->where($model->getQualifiedIsDeletedColumn(), 0);
	}

	/**
	 * Extend the query builder with the needed functions.
	 */
	public function extend(Builder $builder): void
	{
		foreach ($this->extensions as $extension) {
			$this->{"add{$extension}"}($builder);
		}
		$builder->onDelete(function (Builder $builder) {
			return $builder->update([
				$this->getIsDeletedColumn($builder) => 1,
                $this->getDeletedAtColumn($builder) => $builder->getModel()->freshTimestampString(),
			]);
		});
	}

	/**
	 * Get the "is deleted" column for the builder.
	 */
	protected function getIsDeletedColumn(Builder $builder): string
	{
		if (count((array) $builder->getQuery()->joins) > 0) {
			return $builder->getModel()->getQualifiedIsDeletedColumn();
		}
        
		return $builder->getModel()->getIsDeletedColumn();
	}
    /**
     * Add the restore extension to the builder.
     * 
     * 
     * 
     */
    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([
                $builder->getModel()->getIsDeletedColumn() => false, 
                $builder->getModel()->getDeletedAtColumn() => null, 
            ]);
        });
    }

    /**
     * Add the without-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedIsDeletedColumn(), 0
            );

            return $builder;
        });
    }

    /**
     * Add the only-trashed extension to the builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @return void
     */
    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedIsDeletedColumn(), 1
            );

            return $builder;
        });
    }
}
