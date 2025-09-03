<?php
namespace Yaseen\Toolset\Concerns;

use Illuminate\Support\Arr;

trait HasConfigColumn
{

    public static function bootHasConfigColumn()
    {
        static::retrieved(function ($model) {
            if($model->config){
                $model->appends =  array_merge($model->appends, array_keys($model->config));
            }
        });
    }

    public static function collectConfigData($config, $inputData) : array {
        $newData = Arr::only($inputData, static::configKeys);
        return array_merge($config, $newData);
    }


    // public static function configValidationRules() : array {
    //     return [
    //         'slug' => [],
    //         'summary' => [],
    //         'is_commentable' => ['boolean'],
    //         'is_anonymous'   => ['boolean'],
    //         'temporal_reservation' => ['boolean'],
    //     ];
    // }





    public function getTemporalReservationAttribute()
    {
        return Arr::get($this->config, 'temporal_reservation', false);
    }
    public function setTemporalReservationAttribute($v)
    {
        $extra = $this->config;
        $extra['temporal_reservation'] = $v;
        $this->config = $extra;
    }







    public function setSlugAttribute($v)
    {
        $extra = $this->config;
        $extra['slug'] = $v;
        $this->config = $extra;
    }
    public function getSlugAttribute()
    {
        return Arr::get($this->config, 'slug');
    }







    public function setSummaryAttribute($v)
    {
        $extra = $this->config;
        $extra['summary'] = $v;
        $this->config = $extra;
    }
    public function getSummaryAttribute()
    {
        return Arr::get($this->config, 'summary');
    }






    public function setSolutionIdAttribute($v)
    {
        $extra = $this->config;
        $extra['solution_id'] = $v;
        $this->config = $extra;
    }
    public function getSolutionIdAttribute()
    {
        return Arr::get($this->config, 'solution_id', 0);
    }





    public function getIsOpenPollAttribute()
    {
        return (bool) Arr::get($this->config, 'is_open_poll', false);
    }
    public function setIsOpenPollAttribute($v)
    {
        $extra = $this->config;
        $extra['is_open_poll'] = $v;
        $this->config = $extra;
    }





    public function getIsCommentableAttribute()
    {
        return (bool) Arr::get($this->config, 'is_commentable', true);
    }
    public function setIsCommentableAttribute($v)
    {
        $extra = $this->config;
        $extra['is_commentable'] = $v;
        $this->config = $extra;
    }







    public function getIsAnonymousAttribute()
    {
        return (bool) Arr::get($this->config, 'is_anonymous', false);
    }
    public function setIsAnonymousAttribute($v)
    {
        $extra = $this->config;
        $extra['is_anonymous'] = $v;
        $this->config = $extra;
    }








    public function getHasApproavableCommentsAttribute()
    {
        return Arr::get($this->config, 'has_approavable_comments', false);
    }
    public function setHasApproavableCommentsAttribute($v)
    {
        $extra = $this->config;
        $extra['has_approavable_comments'] = $v;
        $this->config = $extra;
    }





}
