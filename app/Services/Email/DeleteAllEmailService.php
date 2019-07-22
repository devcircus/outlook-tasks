<?php

namespace App\Services\Email;

use App\Models\Email;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Models\User;

class DeleteAllEmailService
{
    use SelfCallingService;

    /** @var \App\Models\Email */
    private $email;

    /**
     * Construct a new DeleteAllEmailService.
     *
     * @param  \App\Models\Email  $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     *
     * @return bool
     */
    public function run(User $user)
    {
        return $this->email->deleteAllWithNoTask($user);
    }
}
