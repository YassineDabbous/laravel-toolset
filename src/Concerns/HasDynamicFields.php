<?php
// namespace Ysn\SuperCore\Concerns;
// // use Illuminate\Contracts\Database\Eloquent\Builder; 
// use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;
// use Illuminate\Database\Query\Builder as BaseBuilder;
// use Illuminate\Database\Eloquent\Collection;
// use Illuminate\Support\Arr;

// trait HasDynamicFields{
//     // https://github.com/jesperbjerke/laravel-api-query-builder
    
//     /**
//      * all selectable table columns
//      */
//     public function dynamicColumns(){
//         return []; //'id', 'account_id', 'name', 'icon'
//     }
//     /**
//      * all visible relations
//      */
//     public function dynamicRelations(){
//         return [];
//     }
//     /**
//      * all visible appends with their columns dependencies
//      */
//     public function dynamicAppendsDepsColumns(){
//         return [
//             // 'icon_url' => 'icon' // icon_url depends on 'icon' columns
//         ];
//     }
//     /**
//      * all visible appends with their relations dependencies
//      */
//     public function dynamicAppendsDepsRelations(){
//         return [
//             // 'status_name' => 'status' // status_name depends on 'status' Relation
//         ];
//     }


//     public function dynamicAggregates(){
//         return [
//             // 'employees_count' => fn($q) => $q->withCount('employees'),
//             // 'employees_sum_salary' => fn($q) => $q->withSum('employees', 'salary'),
//         ];
//     }

//     public function scopeDynamicSelect(EloquentBuilder $q) {
//         $list = request()->input('_fields', []);
//         if (is_string($list)) {
//             $list = explode(',', $list);
//         }
//         if(count($list)==0){
//             return $q;
//         }

//         if(count($this->dynamicColumns())){
//             $deps = [];
//             if(count($this->dynamicAppendsDepsColumns())){
//                 $appends = array_intersect(array_keys($this->dynamicAppendsDepsColumns()), $list);
//                 $filtered = array_filter(
//                     $this->dynamicAppendsDepsColumns(),
//                     fn ($key) => in_array($key, $appends),
//                     ARRAY_FILTER_USE_KEY
//                 );
//                 $deps = array_values($filtered);
//             }
//             $columns = [
//                 ...array_intersect($this->dynamicColumns(), $list), 
//                 ...array_intersect($this->dynamicColumns(), $deps)
//             ];
//             // \Log::debug($columns);
//             if(count($columns)){
//                  $q->select(array_unique($columns));
//             }
//         }


//         if(count($this->dynamicRelations())){
//             $deps = [];
//             if(count($this->dynamicAppendsDepsRelations())){
//                 $appends = array_intersect(array_keys($this->dynamicAppendsDepsRelations()), $list);
//                 $filtered = array_filter(
//                     $this->dynamicAppendsDepsRelations(),
//                     fn ($key) => in_array($key, $appends),
//                     ARRAY_FILTER_USE_KEY
//                 );
//                 $deps = array_values($filtered);
//             }
//             $relations = [
//                 ...array_intersect($this->dynamicRelations(), $list), 
//                 ...array_intersect($this->dynamicRelations(), $deps)
//             ];
//             // \Log::debug($relations);
//             if(count($relations)){
//                 $q->with(array_unique($relations));
//             }
//         }

        
//         if(count($this->dynamicAggregates())){
//             $cals = array_intersect(array_keys($this->dynamicAggregates()), $list);
//             foreach ($cals as $value) {
//                 $this->dynamicAggregates()[$value]($q);
//             }
//         }

//         return $q;
//     }

    
//     public function dynamicAppend(array $list = []) {
//         $columns = array_intersect(array_keys($this->dynamicAppendsDepsColumns()), $list);
//         $this->setAppends($columns);
//     }



//     public static function registerMacro()
//     {
//         $macro = function () {
//             $list = request()->input('_fields', []);
//             if (is_string($list)) {
//                 $list = explode(',', $list);
//             }
//             // if(count($list)==0){
//             //     return $this;
//             // }
//             foreach ($this as $key => $value) {
//                 $value->dynamicAppend($list);
//             }
//             // return $this;
//         };

//         Collection::macro('dynamicAppend', $macro);
//     }


//     public static function registerPaginateMacro()
//     {
//         $macro = function (int $maxResults = null, int $defaultSize = null) {
//             $maxResults = $maxResults ?? 30;
//             $defaultSize = $defaultSize ?? 10;
//             $request = request();
//             $size = (int) $request->input('per_page', $defaultSize);
//             if ($size <= 0) {
//                 $size = $defaultSize;
//             }
//             if ($size > $maxResults) {
//                 $size = $maxResults;
//             }
//             if($request->list_all == true){
//                 $this->get();
//             }
//             return $request->input('page', 0) == 1 ? $this->paginate($size) : $this->simplePaginate($size);
//         };

//         EloquentBuilder::macro('superPaginate', $macro);
//         BaseBuilder::macro('superPaginate', $macro);
//         BelongsToMany::macro('superPaginate', $macro);
//         HasManyThrough::macro('superPaginate', $macro);
//     }
// }
