<?php
namespace Ysn\SuperCore\Concerns;

trait HasDifferentAppends
{
    public static function bootHasDifferentAppends()
    {
        static::retrieved(fn($model) => $model->resetAppends());
    }

    public function resetAppends(){
        $this->appends = !isset($this->appendsList[$this->type]) ? $this->appends : array_merge($this->appends, $this->appendsList[$this->type]);
    }

    public function resetAppendsFull(){
        $this->appends = !isset($this->appendsFull[$this->type]) ? $this->appends : array_merge($this->appends, $this->appendsFull[$this->type]);
        // if($this->extra){
        //     // $this->appends =  array_merge($this->appends, array_keys($this->extra));
        //     foreach ($this->extra as $key => $value) {
        //         $this->$key = $value;
        //     }
        // }
    }

}
