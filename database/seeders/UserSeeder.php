<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Admin IndoOptik',
                'email'             => 'admin@indooptik.com',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Staff IndoOptik',
                'email'             => 'staff@indooptik.com',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_STAFF,
                'email_verified_at' => now(),
            ],
            [
                'name'              => 'Pelanggan Demo',
                'email'             => 'user@indooptik.com',
                'password'          => Hash::make('password123'),
                'role'              => User::ROLE_USER,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData,
            );
        }

        $this->command->info('✅ UserSeeder: 3 users seeded (admin, staff, user).');
    }
}
