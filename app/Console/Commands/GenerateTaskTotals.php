<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Total;
use Illuminate\Console\Command;
use App\Services\Cache\CacheForgetService;
use App\Services\Task\GenerateTotalQuantitiesFromTasksService;

class GenerateTaskTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'totals:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate task total quantities from existing tasks.';

    /** @var \App\Models\User */
    private $users;

    /** @var \App\Models\Total */
    private $totals;

    /**
     * Create a new command instance.
     *
     * @param  \App\Models\User  $users
     * @param  \App\Models\Total  $totals
     */
    public function __construct(User $users, Total $totals)
    {
        $this->users = $users;
        $this->totals = $totals;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->users->all();

        foreach($users as $user) {
            CacheForgetService::call('quantities', $user->id);
            $this->totals->forUser($user)->delete();

            return GenerateTotalQuantitiesFromTasksService::call($user);
        }
    }
}
