<?php namespace Ysn\SuperCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait HasQueryHerlpers
{
    public function whereEqualOrArray(Builder &$builder, string $key, $value)
    {
        if(is_array($value))
            $builder->whereIn($key, $value);
        else
            $builder->where($key, $value);
    }

    public function equalMinMax(Builder &$builder, $filter, $key)
    {
        if(isset($filter[$key])){
            $builder->where($key, '=', $filter[$key]);
        }
        else{
            if (isset($filter["{$key}_min"])) {
                $builder->where($key, '>=', $filter["{$key}_min"]);
            }
            if (isset($filter["{$key}_max"])) {
                $builder->where($key, '<=', $filter["{$key}_max"]);
            }
        }
    }





    public function whereJson(Builder &$builder, string $jsonColumn, string $key, $value)
    {
        $column = "$jsonColumn->$key";
        // Log::warning($column);
        if(is_array($value)){
            // Log::warning('json value => is array');
            if(isset($value["from"]) || isset($value["to"])){
                // Log::warning('json value => is range');
                if(isset($value["from"]) && isset($value["to"])){
                    $builder->where(fn($jb) => $jb->where($column, '>=', $value["from"])->orWhere($column, '<=', $value["to"]) );
                } else {
                    if (isset($value["from"])) {
                        $builder->where($column, '>=', $value["from"]);
                    }
                    if (isset($value["to"])) {
                        $builder->where($column, '<=', $value["to"]);
                    }
                }
            } else {
                // Log::warning('json value => is multi');
                // $builder->whereJsonContains("extra->$key", $value);

                // json->year : 2010   |  user search input : [2009, 2010]
                // query ===> where(year, 2009)->orWhere(year, 2010)
                $builder->where(function($q) use ($column, $value){
                    foreach ($value as $v) {
                        $q->orWhere($column, $v);
                    }
                });
            }
        }
        else {
            // Log::warning('json value => is value');
            $builder->where($column, $value);
        }
    }



    // public function equalMinMaxDate(Builder &$builder, $filter, $key)
    // {
    //     if(isset($filter[$key])){
    //         $builder->where($key, '=', Carbon::now()->subYears($filter[$key]));
    //     }
    //     else{
    //         if (isset($filter["{$key}_min"])) {
    //             $builder->where($key, '>=', Carbon::now()->subYears($filter["{$key}_min"]));
    //         }
    //         if (isset($filter["{$key}_max"])) {
    //             $builder->where($key, '<=', Carbon::now()->subYears($filter["{$key}_min"]));
    //         }
    //     }
    // }









    //
    //
    // FOR STATISTICS
    //
    //

    
    protected function perColumn(Builder $q, string $column) : \Illuminate\Support\Collection {
        return $q->selectRaw($column.', count(id) as yTotal')
                    ->groupBy($column)
                    ->pluck('yTotal', $column); 
    }



    protected function perMonth(Builder $q, $column = 'created_at') : \Illuminate\Support\Collection {
        $thisYear = date("Y").'-01-01 00:00:00';
        $q->where($column, '>=', $thisYear);
        $result = $q->selectRaw('DATE_FORMAT('.$column.',"%b") as yMonth, count(id) as yTotal')
                    ->groupBy('yMonth')
                    ->pluck('yTotal', 'yMonth');
                    
        $months = ['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0, 'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0];
        $shortList = [];
        foreach ($months as $key => $value) {
            $shortList[$key] = $value;
            if($key == date('M')){
                break;
            }
        }
        return collect(array_merge($shortList, $result->toArray()));
    }

    // last 5 years
    protected function perYear(Builder $q, $column = 'created_at') : \Illuminate\Support\Collection {
        $thisYear = (((int) date("Y")) - 5).'-01-01 00:00:00';
        $q->where($column, '>=', $thisYear);
        
        return $q->selectRaw('DATE_FORMAT('.$column.',"%Y") as yDate, count(id) as yTotal')
                    ->groupBy('yDate')
                    ->pluck('yTotal', 'yDate');  
    }




}
