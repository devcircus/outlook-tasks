<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\User;
use App\Models\Email;
use Ramsey\Uuid\Uuid;
use Faker\Generator as Faker;

$factory->define(Email::class, function (Faker $faker) {

    return [
        'uuid' => Uuid::uuid4(),
        'outlook_id' => Uuid::uuid4(),
        'subject' => $faker->sentence(rand(2,4)),
        'from_address' => $faker->safeEmail,
        'from_name' => $faker->name,
        'body' => $faker->paragraph(rand(2,5)),
        'received_at' => $faker->dateTimeBetween('-30 days', 'now'),
        'user_id' => factory(User::class),
    ];
});
