<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Http\DTO\Category as CategoryData;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Category\Validation\UpdateCategoryValidationService;

class UpdateCategoryService
{
    use SelfCallingService;

    /** @var \App\Services\Category\Validation\UpdateCategoryValidationService */
    private $validator;

    /**
     * Construct a new UpdateCategoryService.
     *
     * @param  \App\Services\Category\Validation\UpdateCategoryValidationService  $validator
     */
    public function __construct(UpdateCategoryValidationService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\Category  $category
     * @param  \App\Http\DTO\Category  $data
     * @param  \App\Models\User  $user
     *
     * @return mixed
     */
    public function run(Category $category, CategoryData $data, User $user)
    {
        $this->validator->validate(
            $data->with(['id' => $category->id])->toArray()
        );

        return $category->updateCategory($data->only(['name']), $user->id);
    }
}
