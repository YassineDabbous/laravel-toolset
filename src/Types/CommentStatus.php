<?php 
namespace Ysn\SuperCore\Types;

enum CommentStatus : int
{
    const DEFAULT = CommentStatus::PUBLISHED;

    case PUBLISHED = 0;
    case INREVIEW = 1;
    case HIDDEN = 2; // when reported by users for illegal content


    const list = [
        CommentStatus::PUBLISHED->value   =>  'PUBLISHED',
        CommentStatus::HIDDEN->value      =>  'HIDDEN',
        CommentStatus::INREVIEW->value    =>  'INREVIEW',
    ];
    // public static function getStatusName(int $status){
    //     return self::list[$status] ?? 'undefined status';
    // }
}
