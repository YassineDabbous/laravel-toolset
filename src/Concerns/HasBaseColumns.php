<?php
namespace Ysn\SuperCore\Concerns;

trait HasBaseColumns
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

    public function getUnameAttribute()
    {
        return $this->account ? $this->account->name : 'Deleted account';
    }
    public function getUpictureAttribute()
    {
        return $this->account ? $this->account->photo : config('settings.images.avatar');
    }
}
