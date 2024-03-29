<?php

namespace App\Services\Outlook;

use App\Models\User;
use App\Services\Cache\CacheForgetService;
use App\Services\Outlook\FetchEmailFromOutlookService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class SyncEmailService
{
    use SelfCallingService;

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     */
    public function run(User $user): void
    {
        CacheForgetService::call('emails', $user->id);
        CacheForgetService::call('emailQuantities', $user->id);

        FetchEmailFromOutlookService::call($user);
    }
}
