<?php

namespace Yaseen\Toolset\Jobs;

use Yaseen\Toolset\Jobs\BaseJob;

class LogModelViews extends BaseJob
{
    protected $visitorId;
    protected $model;

    public function __construct($model, $visitorId)
    {
        $this->visitorId = $visitorId;
        $this->model = $model;
    }

    public function handle()
    {
        $this->model->logVisit($this->visitorId);
    }
}
