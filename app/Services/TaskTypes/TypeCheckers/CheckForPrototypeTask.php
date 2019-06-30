<?php

namespace App\Services\TaskTypes\TypeCheckers;

use Illuminate\Database\Eloquent\Model;
use PerfectOblivion\Services\Traits\SelfCallingService;

class CheckForPrototypeTask
{
    use SelfCallingService;

    /**
     * Check if the task is for a prototype.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return bool
     */
    public function run(Model $model)
    {
        return;
    }
}
