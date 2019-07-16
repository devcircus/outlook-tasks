<?php

namespace App\Services\User;

use App\Models\User;
use App\Http\DTO\UserData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\User\Validation\StoreUserValidationService;

class StoreUserService
{
    use SelfCallingService;

    /** @var \App\Services\User\Validation\StoreUserValidationService */
    private $validator;

    /** @var \App\Models\User */
    private $users;

    /**
     * Construct a new StoreUserService.
     *
     * @param  \App\Services\User\Validation\StoreUserValidationService  $validator
     * @param  \App\Models\User  $users
     */
    public function __construct(StoreUserValidationService $validator, User $users)
    {
        $this->validator = $validator;
        $this->users = $users;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Http\DTO\UserData  $user
     *
     * @return \App\Models\User
     */
    public function run(UserData $user)
    {
        $this->validator->validate($user->toArray());

        return $this->users->createUser($user);
    }
}
