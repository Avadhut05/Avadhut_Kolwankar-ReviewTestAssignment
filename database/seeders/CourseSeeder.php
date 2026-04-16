<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Java',
                'level' => 'Intermediate',
                'description' => 'Learn Java programming from fundamentals to advanced concepts including OOP, collections, and multithreading.',
            ],
            [
                'name' => 'PHP',
                'level' => 'Beginner',
                'description' => 'Master PHP for web development covering syntax, databases, and modern frameworks.',
            ],
            [
                'name' => 'SQL',
                'level' => 'Beginner',
                'description' => 'Understand relational databases, queries, joins, and database design with SQL.',
            ],
            [
                'name' => 'React',
                'level' => 'Advanced',
                'description' => 'Build modern user interfaces with React including hooks, state management, and component architecture.',
            ],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(
                ['name' => $course['name']],
                $course,
            );
        }
    }
}
