<?php

namespace App\Services\User;

use App\Models\User;
use App\Http\DTO\User as UserData;
use PerfectOblivion\Services\Traits\SelfCallingService;
use App\Services\User\Validation\UpdateUserValidationService;

class UpdateUserService
{
    use SelfCallingService;

    /** @var \App\Services\User\Validation\UpdateUserValidationService */
    private $validator;

    /**
     * Construct a new UpdateUserService.
     *
     * @param  \App\Services\User\Validation\UpdateUserValidationService  $validator
     */
    public function __construct(UpdateUserValidationService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Handle the call to the service.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Http\DTO\User  $data
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
