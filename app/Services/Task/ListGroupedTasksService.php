<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use App\Services\Category\ListCategoriesService;
use PerfectOblivion\Services\Traits\SelfCallingService;

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
     * @param  string  $category
     * @param  string  $calendar
     * @param  \App\Models\User  $user
     *
     * @return array
     */
    public function run(string $category, string $calendar, User $user)
    {
        $category = $this->validateCategory($category);
        $calendar = $this->validateCalendar($calendar);
        $this->tasks->makeHidden(['description']);

        $tasks = $user->tasks()
                ->forCategory($category)
                ->forCalendar($calendar)
                ->withTrashed()
                ->orderByColumn('due_date', 'asc')
                ->get()
                ->toArray();

        return count($tasks) ? ['data' => $tasks, 'categoryName' => $category] : [];
    }

    /**
     * Validate the passed category.
     *
     * @param  string  $category
     */
    private function validateCategory(string $category): string
    {
        $categories = ListCategoriesService::call($withTrashed = false)->pluck('name')->toArray();

        return in_array($category, $categories) ? $category : 'all';
    }

    /**
     * Validate the passed calendar option.
     *
     * @param  string  $calendar
     */
    private function validateCalendar(string $calendar): string
    {
        $options = ['all', 'overdue', 'today', 'week'];

        return in_array($calendar, $options) ? $calendar : 'all';
    }
}
