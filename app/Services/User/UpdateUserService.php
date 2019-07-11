<?php

namespace App\Services\User;

use App\Models\User;
use App\Http\DTO\UserData;
use App\Services\User\Validation\UpdateUserValidation;
use PerfectOblivion\Services\Traits\SelfCallingService;

class UpdateUserService
{
    use SelfCallingService;

    /** @var \App\Services\User\Validation\UpdateUserValidation */
    private $validator;

    /**
     * Construct a new UpdateUserService.
     *
     * @param  \App\Services\User\Validation\UpdateUserValidation  $validator
     */
    public function __construct(UpdateUserValidation $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Http\DTO\UserData  $data
     *
     * @return mixed
     */
    public function run(User $user, UserData $data)
    {
        $this->validator->validate(
            $data->with(['id' => $user->id])->toArray()
        );

        return $user->updateUserData($data);
    }
}
