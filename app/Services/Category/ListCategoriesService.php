<?php

namespace App\Services\Category;

use App\Models\Category;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ListCategoriesService
{
    use SelfCallingService;

    /** @var \App\Models\Category */
    private $categories;

    /**
     * Construct a new ListCategoriesService.
     *
     * @param  \App\Models\Category  $categories
     */
    public function __construct(Category $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Handle the call to the service.
     *
     * @param  bool  $withTrashed
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function run(bool $withTrashed = true)
    {
        return $this->categories->withTrashed($withTrashed)->get();
    }
}
