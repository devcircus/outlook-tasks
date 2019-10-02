<?php

namespace App\Services\Task;

use App\Models\User;
use App\Models\Total;
use Illuminate\Support\Collection;
use PerfectOblivion\Services\Traits\SelfCallingService;

class GetTotalQuantitiesFromDatabaseService
{
    use SelfCallingService;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Construct a new GetTotalQuantitiesFromDatabaseService.
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
        return $this->totals->getTotals($user);
    }
}
