<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = factory(User::class, 10)->create();
        $admin = $this->createDefaultAdmin();
        $this->createTasksForAdmin($admin);
    }

    /**
     * Seed the application's admin user.
     *
     * @return \App\Models\User
     */
    public function createDefaultAdmin()
    {
        return factory(User::class)->create([
            'name' => config('auth.admin.name'),
            'email' => config('auth.admin.email'),
            'password' => bcrypt(config('auth.admin.password')),
            'is_admin' => true,
        ]);
    }

    /**
     * Seed the admin's tasks.
     */
    public function createTasksForAdmin(User $user)
    {
        factory(Task::class, 15)->create([
            'user_id' => $user->id,
        ]);
    }
}
