<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Events\EmailCategoriesSet;
use Illuminate\Support\Collection;
use PerfectOblivion\Services\Traits\SelfCallingService;

class SetCategoryService
{
    use SelfCallingService;

    /** @var \App\Models\Category */
    private $categories;

    /**
     * Construct a new SetCategoryService.
     *
     * @param  \App\Models\Category  $categories
     */
    public function __construct(Category $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Get all task types for the given task.
     *
     * @param  \Illuminate\Support\Collection  $emails
     *
     * @return array
     */
    public function run(Collection $emails)
    {
        $categories = $this->categories->ready()->get();

        $emails->each(function ($email) use ($categories) {
            $check = true;
            while ($check) {
                foreach($categories as $category) {
                    if (CheckCategoryService::call($email, $category)) {
                        $email->setCategory($category);
                        $check = false;
                    }
                }
                $email->setProcessed();
                $check = false;
            }
        });

        EmailCategoriesSet::broadcast();
    }
}
