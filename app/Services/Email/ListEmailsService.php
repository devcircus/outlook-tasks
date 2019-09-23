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
        return $user->emails()->withTrashed()->withNoTask()->orderByColumn('received_at', 'desc')->get(['id', 'received_at', 'deleted_at', 'subject', 'from_address', 'from_name', 'uuid', 'category_id', 'user_id']);
    }
}
