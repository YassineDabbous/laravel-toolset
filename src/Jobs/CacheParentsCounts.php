<?php

namespace Yaseen\Toolset\Jobs;

use Yaseen\Toolset\Jobs\BaseJob;

class CacheParentsCounts extends BaseJob
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model->withoutRelations();
    }

    public function handle()
    {
        $this->model->cacheParentsCounts();
    }
}
