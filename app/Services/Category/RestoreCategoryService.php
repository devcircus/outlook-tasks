<?php

namespace App\Services\Category;

use App\Models\Category;
use PerfectOblivion\Services\Traits\SelfCallingService;

class RestoreCategoryService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Category  $category
     *
     * @return \App\Models\Category
     */
    public function run(Category $category)
    {
        return $category->restoreCategory();
    }
}
