<?php

namespace Ysn\SuperCore\Jobs;

use Ysn\SuperCore\Jobs\BaseJob;

class NotifyConcerned extends BaseJob
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model->withoutRelations();
    }

    public function handle()
    {
        $this->model->notifyConcerned();
    }
}
