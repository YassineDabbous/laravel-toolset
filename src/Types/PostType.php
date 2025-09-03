<?php
namespace Ysn\SuperCore\Types;


abstract class PostType
{
    const ALL = [
        PostType::UNDEFINED, 
        PostType::ARTICLE, 
        PostType::TWEET, 
        PostType::POLL,
        PostType::EVENT, 
        PostType::HELP, 
        PostType::PRODUCT,
        PostType::SERVICE, 
        PostType::WORK, 
    ];

    public static function requireCategory(int $type): bool
    {
        return in_array($type, [
            PostType::ARTICLE,
            PostType::HELP,
            PostType::PRODUCT, 
            PostType::SERVICE, 
            PostType::WORK
        ]);
    }


    const UNDEFINED = 0; // GENERAL (accept all fields)
    const ARTICLE = 1; // title, content
    const TWEET = 2; // POST, VIDEO, STORY
    const POLL = 3; // has options
    const EVENT = 4; // started_at ends_at  + can have appeals
    const HELP = 5; // LOST, FOUND, QUESTION, HELP_REQUEST, URGENT
    const PRODUCT = 6; // product_variant, product_master
    const SERVICE = 7; // SELL, BUY, RENT
    const WORK = 8; // JOB_OFFER, JOB_REQUEST, IDEA, WORK

    

} 