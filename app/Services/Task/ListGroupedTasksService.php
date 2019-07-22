<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\Category\ListCategoriesService;

class ListGroupedTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Tasks */
    private $tasks;

    /**
     * Construct a new ListGroupedTasksService.
     *
     * @param  \App\Models\Tasks  $tasks
     */
    public function __construct(Task $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return array
     */
    public function run(User $user)
    {
        $categories = ListCategoriesService::call($withTrashed = false);

        $result = $categories->mapWithKeys(function ($category) use ($user) {
            return  [
                $category->name => $user->tasks()->forCategory($category->name)->withTrashed()->orderByColumn('due_date', 'asc')->get(),
            ];
        });

        return $result->count() > 0 ? $result : null;
    }
}
