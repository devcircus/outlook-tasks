<?php

namespace App\Services\TaskTypes;

use Illuminate\Database\Eloquent\Model;
use App\Services\TaskTypes\TypeCheckers;
use PerfectOblivion\Services\Traits\SelfCallingService;

class SetTaskTypes
{
    use SelfCallingService;

    /** @var array */
    public $checkers = [
        'swatch' => TypeCheckers\CheckForSwatchTask::class,
        'vsf' => TypeCheckers\CheckForVSFTask::class,
        'prototype' => TypeCheckers\CheckForPrototypeTask::class,
    ];

    /**
     * Get all task types for the given task.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return array
     */
    public function run(Model $model)
    {
        $found = collect($this->checkers)->filter(function ($checker) use ($model) {
            return $checker::call($model);
        })->keys()->toArray();

        $types = collect($found)->map(function($type) {
            return resolve('types')[$type]->id;
        });

        return $model->types()->sync($types);
    }
}
