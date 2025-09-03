<?php namespace Ysn\SuperCore\Types;


enum PostStatus : int
{
    case DRAFT     = 0;
    case PUBLISHED = 1;
    case INREVIEW  = 2;
    case SCHEDULED = 3;
    case PAUSED    = 4;
    case HIDDEN    = 5; // when reported by users for illegal content
    case EXPIRED   = 6; // ??? expired should be deleted
    case UNAVAILABLE    = 7; // eg: product quantity = 0
    
    case HIDDEN_FROM_FEED = 8; // for stories, reels


    // const list = [
    //     PostStatus::DRAFT       =>  'DRAFT',
    //     PostStatus::PUBLISHED   =>  'PUBLISHED',
    //     PostStatus::SCHEDULED   =>  'SCHEDULED',
    //     PostStatus::PAUSED      =>  'PAUSED',
    //     PostStatus::HIDDEN      =>  'HIDDEN',
    //     PostStatus::INREVIEW    =>  'INREVIEW',
    //     PostStatus::UNAVAILABLE    =>  'UNAVAILABLE',
    // ];
    // public static function getStatusName(int $status){
    //     return self::list[$status] ?? 'undefined status';
    // }
}
