<?php namespace Ysn\SuperCore\Types;



abstract class ReviewStatus
{
    const DEFAULT = ReviewStatus::PUBLISHED;

    const PUBLISHED = 0;
    const INREVIEW = 1;
    const HIDDEN = 2; // when reported by users for illegal content


    const list = [
        ReviewStatus::PUBLISHED   =>  'PUBLISHED',
        ReviewStatus::HIDDEN      =>  'HIDDEN',
        ReviewStatus::INREVIEW    =>  'INREVIEW',
    ];
    public static function getStatusName(int $status){
        return self::list[$status] ?? 'undefined status';
    }
}
