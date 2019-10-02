<?php

namespace App\Models;

use Illuminate\Support\Collection;
use App\Services\Category\ListCategoriesService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Total extends Model
{
    protected $table = 'totals';

    /**
     * A Total belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new Total.
     *
     * @param  \App\Models\User  $user
     * @param  string  $calendarOption
     * @param  string  $category
     * @param  int  $total
     */
    public function storeTotal(User $user, string $calendarOption, string $category, int $total): Total
    {
        return $this->updateOrCreate(['category' => $category, 'calendar_option' => $calendarOption], [
            'total' => $total,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Get all of the totals.
     *
     * @param  \App\Models\User  $user
     */
    public function getTotals(User $user): Collection
    {
        $categories = collect(ListCategoriesService::call($withTrashed = false)->pluck('name')->push('all'));
        $calendarOptions = collect(['all', 'overdue', 'today', 'week']);

        $calendarCategoryQuantities = [];
        foreach ($calendarOptions as $calendarOption) {
            $calendarCategoryQuantities[$calendarOption] = [];
            foreach ($categories as $category) {
                $item = $this->where('category', $category)
                    ->where('calendar_option', $calendarOption)
                    ->where('user_id', $user->id)
                    ->first();

                $calendarCategoryQuantities[$calendarOption][$category] = $item ? $item->total : 0;
            }
        }

        return collect($calendarCategoryQuantities);
    }
}
