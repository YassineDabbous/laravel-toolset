<?php

namespace Yaseen\Toolset\Concerns;

use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletesParent;

trait SoftDeletes
{
    use SoftDeletesParent;
    
    /**
     * who delete me?
     */
    public function deleter()
    {
        return $this->belongsTo(accountClass(), 'deleter_id');
    }

	/**
	 * Boot the soft deleting trait for a model.
	 */
	public static function bootSoftDeletes(): void
	{
		static::addGlobalScope(new SoftDeletingBooleanScope());
	}

    /**
     * Initialize the soft deleting trait for an instance.
     */
    public function initializeSoftDeletes()
    {
        if (! isset($this->casts[$this->getDeletedAtColumn()])  ||  ! isset($this->casts[$this->getIsDeletedColumn()])) {
            $this->casts[$this->getDeletedAtColumn()] = 'datetime';
            $this->casts[$this->getIsDeletedColumn()] = 'boolean';
        }
    }

    /**
     * Perform the actual delete query on this model instance.
     */
    protected function runSoftDelete()
    {
        $query = $this->setKeysForSaveQuery($this->newModelQuery());

        $time = $this->freshTimestamp();

        $columns = [
            $this->getDeletedAtColumn() => $this->fromDateTime($time),
            $this->getIsDeletedColumn() => true,
            $this->getDeleterIDColumn() => auth()->check() ? auth()->id() : 0,
        ];

        // $this->{$this->getDeletedAtColumn()} = $time;
        // $this->{$this->getIsDeletedColumn()} = true;
        // $this->{$this->getDeleterIDColumn()} = auth()->check() ? auth()->id() : 0;

        if ($this->usesTimestamps() && ! is_null($this->getUpdatedAtColumn())) {
            $this->{$this->getUpdatedAtColumn()} = $time;
            $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
        }

        $query->update($columns);

        $this->syncOriginalAttributes(array_keys($columns));

        $this->fireModelEvent('trashed', false);
    }




    /**
     * Restore a soft-deleted model instance.
     */
    public function restore() : bool
    {
        // If the restoring event does not return false, we will proceed with this
        // restore operation. Otherwise, we bail out so the developer will stop
        // the restore totally. We will clear the deleted timestamp and save.
        if ($this->fireModelEvent('restoring') === false) {
            return false;
        }

        $this->{$this->getDeletedAtColumn()} = null;
        $this->{$this->getIsDeletedColumn()} = false;

        // Once we have saved the model, we will fire the "restored" event so this
        // developer will do anything they need to after a restore operation is
        // totally finished. Then we will return the result of the save call.
        $this->exists = true;

        $result = $this->save();

        $this->fireModelEvent('restored', false);

        return $result;
    }





    /**
     * Get the name of the "is deleted" column.
     */
    public function getIsDeletedColumn() : string
    {
        return 'is_deleted';
    }

    /**
     * Get the fully qualified "is deleted" column.
     */
    public function getQualifiedIsDeletedColumn() : string
    {
        return $this->qualifyColumn($this->getIsDeletedColumn());
    }

    
    /**
     * Get the name of the "is deleted" column.
     */
    public function getDeleterIDColumn() : string
    {
        return 'is_deleted';
    }


}
