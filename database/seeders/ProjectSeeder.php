<?php

namespace Database\Seeders;

use App\Enums\Project\ProjectPriority;
use App\Enums\Project\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'Test Project',
            'status' => ProjectStatus::ACTIVE,
            'priority' => ProjectPriority::MEDIUM,
            'client_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'budget' => random_int(10000, 999999999),
        ]);
    }
}
