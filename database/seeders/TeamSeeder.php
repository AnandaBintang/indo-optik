<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = [
            [
                'name'   => 'Anisa Rahma',
                'role'   => 'Founder & CEO',
                'photo'  => null,
                'status' => 'published',
            ],
            [
                'name'   => 'Raka Pratama',
                'role'   => 'Head Optometrist',
                'photo'  => null,
                'status' => 'published',
            ],
            [
                'name'   => 'Dwi Anggraeni',
                'role'   => 'Customer Experience Lead',
                'photo'  => null,
                'status' => 'published',
            ],
            [
                'name'   => 'Fajar Nugroho',
                'role'   => 'Product & Lens Specialist',
                'photo'  => null,
                'status' => 'published',
            ],
            [
                'name'   => 'Maya Putri',
                'role'   => 'Operations Manager',
                'photo'  => null,
                'status' => 'published',
            ],
        ];

        foreach ($teams as $team) {
            Team::updateOrCreate(
                ['name' => $team['name'], 'role' => $team['role']],
                $team,
            );
        }
    }
}
