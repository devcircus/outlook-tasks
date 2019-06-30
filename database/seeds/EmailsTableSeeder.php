<?php

use App\Models\Email;
use Illuminate\Database\Seeder;

class EmailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\Models\User::get();

        $users->each(function ($user) {
            factory(Email::class, 10)->create(['user_id' => $user->id]);
        });
    }
}
