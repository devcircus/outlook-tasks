<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Total;
use Illuminate\Support\Collection;
use App\Services\Category\ListCategoriesService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class GenerateTotalQuantitiesFromTasksService
{
    use SelfCallingService;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Construct a new GenerateTotalQuantitiesFromTasksService.
     *
     * @param  \App\Models\Total  $totals
     */
    public function __construct(Total $totals)
    {
        $this->totals = $totals;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     */
    public function run(User $user): Collection
    {
        $categories = collect(ListCategoriesService::call($withTrashed = false)->pluck('name')->push('all'));
        $calendarOptions = collect(['all', 'overdue', 'today', 'week']);

        $totals = $this->getTotals($user, $calendarOptions, $categories);

        $this->storeGeneratedTotals($user, $totals);

        return $totals;
    }

    /**
     * Get the total quantities for each calendar option for each category.
     *
     * @param  App\Models\User  $user
     * @param  \Illuminate\Support\Collection $calendarOptions
     * @param  \Illuminate\Support\Collection $categories
     */
    protected function getTotals(User $user, Collection $calendarOptions, Collection $categories): Collection
    {
        $calendarCategoryQuantities = [];

        foreach ($calendarOptions as $calendarOption) {
            $calendarCategoryQuantities[$calendarOption] = [];
            foreach ($categories as $category) {
                $calendarCategoryQuantities[$calendarOption][$category] = $user->tasks()->select(['id'])->incomplete()->forCategory($category)->forCalendar($calendarOption)->get()->count();
            }
        }

        return collect($calendarCategoryQuantities);
    }

    /**
     * Store the generated totals in the database.
     *
     * @param  \App\Models\User  $user
     * @param  array  $totals
     */
    public function storeGeneratedTotals(User $user, $totals): void
    {
        foreach ($totals as $calendarOption => $categories) {
            foreach ($categories as $category => $total) {
                $this->totals->storeTotal($user, $calendarOption, $category, $total);
            }
        }
    }
}
