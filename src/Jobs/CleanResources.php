<?php

namespace Ysn\SuperCore\Jobs;

use Ysn\SuperCore\Jobs\BaseJob;

class CleanResources extends BaseJob
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model->withoutRelations();
    }

    public function handle()
    {
        $this->model->cleanResources();
    }
}
