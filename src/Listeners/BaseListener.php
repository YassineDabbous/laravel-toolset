<?php

namespace Yaseen\Toolset\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BaseListener implements ShouldQueue
{
    use InteractsWithQueue;
    public $afterCommit = true;

    public function __construct(){}

    /*public function failed($event, $exception)
    {
        echo 'MessageCreatedListener FAILED';
    }*/
}
