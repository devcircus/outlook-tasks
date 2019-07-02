<?php

use App\Models\User;
use App\Models\Task;
use Ramsey\Uuid\Uuid;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/* @var $factory \Illuminate\Database\Eloquent\Factory */
$factory->define(Task::class, function (Faker $faker) {
    $title = ucfirst(implode(' ', $faker->words(rand(2,4))));
    $slug = Str::slug($title);

    return [
        'title' => $title,
        'uuid' => Uuid::uuid4(),
        'slug' => $slug,
        'description' => $faker->paragraph(rand(2,6)),
        'report_to' => $faker->name,
        'due_date' => $faker->dateTimeBetween('now', '+30 days'),
        'complete' => 0,
        'category_id' => Category::all()->count() > 0 ? Category::all()->random()->id : factory(Category::class),
        'user_id' => factory(User::class),
    ];
});

$factory->afterCreating(Task::class, function ($task, $faker) {
    $task->departments()->save(factory(Department::class)->make());
});
