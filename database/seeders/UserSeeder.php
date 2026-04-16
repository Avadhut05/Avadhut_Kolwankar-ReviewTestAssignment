<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $instructorRole = Role::firstOrCreate(['name' => 'instructor']);
         // Create Admin
    $admin = User::firstOrCreate(
        ['email' => 'admin@test.com'],
        [
            'name' => 'Admin',
            'password' => Hash::make('password'),
        ]
    );

    $admin->assignRole($adminRole);

    // Create Instructors
    for ($i = 1; $i <= 5; $i++) {

        $user = User::create([
            'name' => "Instructor $i",
            'email' => "instructor$i@test.com",
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($instructorRole);

        Instructor::create([
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id,
        ]);
    }
    }
}
