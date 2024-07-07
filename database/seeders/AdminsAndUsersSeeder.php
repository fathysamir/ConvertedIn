<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsAndUsersSeeder extends Seeder
{
    public function run()
    {
        // Create 100 admins with the "admin" role
        $adminRole = Role::where('name', 'admin')->first();
        User::factory()->count(100)->create()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });

        // Create 10,000 users with the "user" role
        $userRole = Role::where('name', 'user')->first();
        User::factory()->count(10000)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });
    }
}