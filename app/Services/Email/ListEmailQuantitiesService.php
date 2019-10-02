<?php

namespace App\Services\Email;

use App\Models\Email;
use App\Models\User;
use App\Services\Cache\CacheForeverService;
use PerfectOblivion\Services\Traits\SelfCallingService;

class ListEmailQuantitiesService
{
    use SelfCallingService;

    /**
     * Construct a new ListEmailQuantitiesService.
     *
     * @param  \App\Models\Email  $emails
     */
    public function __construct(Email $emails)
    {
        $this->emails = $emails;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     */
    public function run(User $user): array
    {
        return CacheForeverService::call('emailQuantities', function() use ($user) {
            return [ 'all' => $this->emails->forUser($user)->withNoTask()->get()->count() ];
        }, $user->id);
    }
}
