<?php

namespace App\Services\Pdf;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Services\Category\ListCategoriesService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ShowTaskListPdfService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     * @param  string|null  $type
     *
     * @return mixed
     */
    public function run(User $user, ? string $type)
    {
        return $type ? $this->getTasksForCategory($user, $type) : $this->getAllTasks($user);
    }

    /**
     * Get the tasks for the given user for a specific category.
     *
     * @param  \App\Models\User  $user
     * @param  string  $type
     */
    private function getTasksForCategory(User $user, string $type): Collection
    {
        $data = $user->tasks()->forCategory($type)->incomplete()->orderByColumn('due_date', 'asc')->get();

        return collect([
            $type => $data
        ]);
    }

    /**
     * Get all tasks for the given user.
     *
     * @param  \App\Models\User  $user
     * @param  string  $type
     */
    private function getAllTasks(User $user): Collection
    {
        $categories = ListCategoriesService::call($withTrashed = false);

        return $categories->mapWithKeys(function ($category) use ($user) {
            return  [
                $category->name => $user->tasks()->forCategory($category->name)->incomplete()->orderByColumn('due_date', 'asc')->get(),
            ];
        });
    }
}
