<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;

class DeleteCategoryService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Category  $category
     * @param  \App\Models\User  $user
     *
     * @return \App\Models\Category
     */
    public function run(Category $category, User $user)
    {
        return $category->deleteCategory($user->id);
    }
}
