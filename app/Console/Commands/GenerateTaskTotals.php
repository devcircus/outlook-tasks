<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Cache\CacheForgetService;
use Illuminate\Console\Command;
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

    /**
     * Create a new command instance.
     *
     * @param  \App\Models\User  $users
     *
     * @return void
     */
    public function __construct(User $users)
    {
        $this->users = $users;

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

            return GenerateTotalQuantitiesFromTasksService::call($user);
        }
    }
}
