<?php
namespace Yaseen\Toolset\Concerns;

use Illuminate\Support\Arr;

trait HasMetaColumn
{

    public static function bootHasMetaColumn()
    {
        static::retrieved(function ($model) {
            if($model->meta){
                $model->appends =  array_merge($model->appends, array_keys($model->meta));
            }
        });
    }

    // public function mergeMetaColumns(){
    //     $this->appends =  array_merge($this->appends, array_keys($this->meta));
    //     // foreach ($this->extra as $key => $value) {
    //     //     $this->$key = $value;
    //     // }
    // }

    //$metaAccount = ['friendsCount', 'followersCount', 'followingCount', 'reviewsCount',  'viewsCount'];
    //$metaPost = ['reactionsCount', 'comments___count', 'reviewsCount',  'viewsCount'];

    public function updateMetaData($key, $value){
        $this->newQuery()->where('id', $this->id)->update(['meta->'.$key => $value]);
    }


    public function getPostablesCountAttribute()
    {
        return Arr::get($this->meta, 'postables_count', 0);
    }
    public function setPostablesCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['postables_count'] = $count;
        $this->meta = $meta;
    }

    

    public function getPostsCountAttribute()
    {
        return Arr::get($this->meta, 'posts_count', 0);
    }
    public function setPostsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['posts_count'] = $count;
        $this->meta = $meta;
    }



    public function getReactionsCountAttribute()
    {
        return Arr::get($this->meta, 'reactions_count', 0);
    }
    public function setReactionsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['reactions_count'] = $count;
        $this->meta = $meta;
    }






    public function getCommentsCountAttribute()
    {
        return Arr::get($this->meta, 'comments_count', 0);
    }
    public function setCommentsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['comments_count'] = $count;
        $this->meta = $meta;
    }





    public function getReviewsCountAttribute()
    {
        return Arr::get($this->meta, 'reviews_count', 0);
    }
    public function setReviewsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['reviews_count'] = $count;
        $this->meta = $meta;
    }



    public function getAppealsCountAttribute()
    {
        return Arr::get($this->meta, 'appeals_count', 0);
    }
    public function setAppealsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['appeals_count'] = $count;
        $this->meta = $meta;
    }


    public function getViewsCountAttribute()
    {
        return Arr::get($this->meta, 'views_count', 0);
    }
    public function setViewsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['views_count'] = $count;
        $this->meta = $meta;
    }





    public function getOptionsCountAttribute()
    {
        return Arr::get($this->meta, 'options_count', 0);
    }
    public function setOptionsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['options_count'] = $count;
        $this->meta = $meta;
    }

    public function getVotesCountAttribute()
    {
        return Arr::get($this->meta, 'votes_count', 0);
    }
    public function setVotesCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['votes_count'] = $count;
        $this->meta = $meta;
    }



    public function getFriendsCountAttribute()
    {
        return Arr::get($this->meta, 'friends_count', 0);
    }
    public function setFriendsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['friends_count'] = $count;
        $this->meta = $meta;
    }





    public function getFollowersCountAttribute()
    {
        return Arr::get($this->meta, 'followers_count', 0);
    }
    public function setFollowersCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['followers_count'] = $count;
        $this->meta = $meta;
    }





    public function getFollowingCountAttribute()
    {
        return Arr::get($this->meta, 'following_count', 0);
    }
    public function setFollowingCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['following_count'] = $count;
        $this->meta = $meta;
    }



    public function getAccountsCountAttribute()
    {
        return Arr::get($this->meta, 'accounts_count', 0);
    }
    public function setAccountsCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['accounts_count'] = $count;
        $this->meta = $meta;
    }


    
    public function getChildrenCountAttribute()
    {
        return Arr::get($this->meta, 'children_count', 0);
    }
    public function setChildrenCountAttribute(int $count)
    {
        $meta = $this->meta;
        $meta['children_count'] = $count;
        $this->meta = $meta;
    }

}
