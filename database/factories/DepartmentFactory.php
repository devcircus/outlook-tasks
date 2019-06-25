<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Department;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Department::class, function (Faker $faker) {
    $name = ucfirst($faker->word);
    $slug = Str::slug($name);

    return [
        'name' => $name,
        'slug' => $slug,
    ];
});
