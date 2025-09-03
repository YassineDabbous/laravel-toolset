<?php
namespace Yaseen\Toolset\Concerns;



trait HasConcealableColumns
{

    public function account()
    {
        return $this->belongsTo(config('qarya.account'), 'account_id');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Ice age';
    }
    public function getUonlineAttribute()
    {
        return $this->account ? $this->account->online : false;
    }


    public function getAccountIdAttribute($attr)
    {
        return $attr;
        // this will cause mistakes in database when saving or account_id for other needs
        // return $this->is_anonymous ? 0 : $attr; 
    }

    public function getUnameAttribute()
    {
        if($this->is_anonymous){
            return 'Anonymous Account';
        }
        return $this->account ? $this->account->name : 'Deleted account';
    }

    public function getUpictureAttribute()
    {
        if($this->is_anonymous){
            return config('settings.images.avatar');
        }
        return $this->account ? $this->account->photo : config('settings.images.avatar');
    }






}
