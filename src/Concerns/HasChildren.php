<?php
namespace Ysn\SuperCore\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait HasChildren
{

    
    public static function bootHasChildren()
    {
        static::created(function($model) {
            if($parent = $model->parent){
                $parent->children_count = $model->parent->children()->count();
                $parent->saveQuietly();
            }
        });
        static::deleted(function($model) {
            if($parent = $model->parent){
                $parent->children_count = $model->parent->children()->count();
                $parent->saveQuietly();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(get_class(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_class(), 'parent_id');
    }

    // public function allChildren()
    // {
    //     return $this->hasMany(get_class(), 'parent_id')->with('allChildren');
    // }


    public function getAllChildren() : Collection
    {
        $children = new Collection();
        foreach ($this->children as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildren());
        }
        return $children;
    }

    // function clearCache(){
    //     # TODO clear by tenant
    // }

    public function getAllIds() : array {
        $cacheKey = $this->getTable().'-'.$this->id.'-children-ids'; // Log::debug($cacheKey);
        if(Cache::has($cacheKey)){
            $ids = Cache::get($cacheKey);
            array_push($ids, $this->id);
            return $ids;
        }
        if($this->children->count()){
            $ids = Cache::rememberForever($cacheKey, function () {
                return $this->getAllChildren()->pluck('id')->toArray();
            });
            // $ids = $this->getAllChildren()->pluck('id')->toArray();
            // Cache::forever($cacheKey, $ids);
            array_push($ids, $this->id);
            return $ids;
        }
        return [$this->id];
    }





    public function getParentsIds() : array {
        $ids = [];
        if(!$this->parent_id){
            return $ids;
        }
        array_push($ids, $this->parent_id);
        return array_merge($ids, $this->parent?->getParentsIds() ?? []);
    }

    public function getAllParentsIds() : array {
        if(!$this->parent_id){
            return [$this->id];
        }
        $cacheKey = $this->getTable().'-'.$this->id.'-parents-ids'; // Log::debug($cacheKey);
        if(Cache::has($cacheKey)){
            $ids = Cache::get($cacheKey);
            array_push($ids, $this->id);
            return $ids;
        }
        if(count($ids =  $this->getParentsIds())){
            Cache::forever($cacheKey, $ids);
            array_push($ids, $this->id);
            return $ids;
        }
        return [$this->id];
    }



}
