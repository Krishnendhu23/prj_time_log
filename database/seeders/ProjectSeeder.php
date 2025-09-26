<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            ['name' => 'Project A', 'status' => 1],
            ['name' => 'Project B', 'status' => 1],
            ['name' => 'Project C', 'status' => 1],
            ['name' => 'Project D', 'status' => 1],
        ];

        foreach ($projects as $proj) {
            Project::create($proj);
        }
    }
}
