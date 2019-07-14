<?php

namespace App\Services\Category;

use App\Events\EmailCategoriesSet;
use Illuminate\Support\Collection;
use PerfectOblivion\Services\Traits\SelfCallingService;

class SetCategoryService
{
    use SelfCallingService;

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
                foreach(resolve('checkers') as $key => $checker) {
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
