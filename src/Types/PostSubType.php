<?php
namespace Ysn\SuperCore\Types;

enum PostSubType : int
{
    const UNDEFINED = 0; // GENERAL (accept all fields)

    const POST = 1;
    const VIDEO = 1;
    const STORY = 1;

    // FEED
    const NEWS = 11;            // == ARTICLE
    const LIVE  = 19;           // title, content, stream_link

    // MARKET
    const SELL = 7;
    const BUY = 8;
    const RENT = 9;

    const PRODUCT_MASTER = 10;
    const PRODUCT_VARIANT = 10;         // price + color, size, qyt, tax ... + can have rating


    // WORK
    const JOB_OFFER = 15;       // QUESTION + PRODUCT: title
    const JOB_REQUEST = 16;     // == JOB_OFFER
    const WORK = 17;            // title, content, date, category ... link, price
    const IDEA = 18;            //


    // REQUEST & RESPONSE
    // HELP
    const LOST = 5;
    const FOUND = 6;
    const QUESTION = 12;        // == ARTICLE + solution
    const HELP = 13;            // == QUESTION
    const URGENT = 14;          // QUESTION


}
