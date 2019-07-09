<?php

namespace App\Services\Email;

use App\Models\Email;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Models\User;

class ListEmailsService
{
    use SelfCallingService;

    /** @var \App\Models\Email */
    private $emails;

    /**
     * Construct a new ListEmailsService.
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
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function run(User $user)
    {
        return $user->emails()->withNoTask()->orderByColumn('received_at', 'desc')->get();
    }
}
