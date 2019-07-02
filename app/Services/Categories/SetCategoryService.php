<?php

namespace App\Services\Categories;

use App\Events\EmailCategoriesSet;
use Illuminate\Support\Collection;
use App\Services\Categories\CategoryCheckers;
use PerfectOblivion\Services\Traits\SelfCallingService;

class SetCategoryService
{
    use SelfCallingService;

    /** @var array */
    public $checkers = [
        'vsf' => CategoryCheckers\CheckForVsfCategory::class,
        'swatch' => CategoryCheckers\CheckForSwatchCategory::class,
        'prototype' => CategoryCheckers\CheckForPrototypeCategory::class,
        'ozone' => CategoryCheckers\CheckForOzoneCategory::class,
        'none' => CategoryCheckers\CheckForNoCategory::class,
    ];

    /**
     * Get all task types for the given task.
     *
     * @param  \Illuminate\Support\Collection  $emails
     *
     * @return array
     */
    public function run(Collection $emails)
    {
        $emails->each(function($email) {
            $check = true;
            while ($check) {
                $matched = false;
                foreach($this->checkers as $key => $checker) {
                    if ($checker::call($email)) {
                        $email->setCategory($key);
                        $check = false;
                        $matched = true;
                    }
                }
                if (! $matched) {
                    $email->setCategory('none');
                }
                $check = false;
            }
        });

        EmailCategoriesSet::broadcast();
    }
}
