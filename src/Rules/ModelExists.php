<?php

namespace Ysn\SuperCore\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ModelExists implements Rule
{
    public function __construct(
        private $model = null,
        private ?string $key = null,
        private ?string $table = null,
        private ?string $cnx = null,
        private bool $ignoreZero = false,
    ){}

    public function passes($attribute, $value)
    {
        if($this->ignoreZero && $value == 0){
            return true;
        }
        if($this->model ==null && $this->table ==null){
            throw new \Exception('ModelExists Rule need modal or table');
        }
        $column = $this->key ?? ($this->model !=null ? (new $this->model)->getKeyName() : 'id');
        // $column = $column ?? 'id';
        //dump($column);
        if($this->table !=null){
            return DB::connection($this->cnx)->table($this->table)->where($column, $value)->exists();
        }
        return $this->model::query()->where($column, $value)->exists();
    }


    public function message()
    {
        return ':attribute !exists.';
    }




    // public function model($class) : self {
    //     $this->model = $class;
    //     return $this;
    // }

    // public function orZero() : self {
    //     $this->ignoreZero = true;
    //     return $this;
    // }

}
