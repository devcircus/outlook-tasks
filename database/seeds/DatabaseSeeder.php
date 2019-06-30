<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(TasksTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(EmailsTableSeeder::class);
    }
}
