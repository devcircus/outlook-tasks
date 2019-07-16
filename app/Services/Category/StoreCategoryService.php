<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Http\DTO\CategoryData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Category\Validation\StoreCategoryValidationService;

class StoreCategoryService
{
    use SelfCallingService;

    /** @var \App\Models\Category */
    private $categories;

    /**
     * Construct a new StoreCategoryService.
     *
     * @param  \App\Models\Category  $categories
     * @param  \App\Services\Category\Validation\StoreCategoryValidationService  $validator
     */
    public function __construct(Category $categories, StoreCategoryValidationService $validator)
    {
        $this->categories = $categories;
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Http\DTO\CategoryData  $category
     *
     * @return \App\Models\Category
     */
    public function run(CategoryData $category)
    {
        $this->validator->validate($category->toArray());

        return $this->categories->createCategory($category->only(['name']));
    }
}
