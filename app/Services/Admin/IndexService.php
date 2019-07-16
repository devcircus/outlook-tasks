<?php

namespace App\Services\Admin;

use App\Models\Category;
use PerfectOblivion\Services\Traits\SelfCallingService;

class IndexService
{
    use SelfCallingService;

    /** @var \App\Models\Category */
    private $categories;

    /**
     * Construct a new IndexService.
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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function run()
    {
        return $this->categories->withTrashed()->get();
    }
}
