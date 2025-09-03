<?php namespace Ysn\SuperCore\Types;


abstract class ActivityType
{
    const CREATE = 0;
    const UPDATE = 1;
    const DELETE = 2;

    const LIKE = 3;
    const COMMENT = 4;
    const REVIEW = 5;
    const FOLLOW = 6;
    const ADD_POLL_OPTION = 7;
    const VOTE = 8;
}
