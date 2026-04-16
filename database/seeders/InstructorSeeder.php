<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::factory()->count(5)->create();
        Instructor::create([
            'name'     => 'John Doe',
            'email'    => 'john.doe@example.com',
        ]);
    }
}
