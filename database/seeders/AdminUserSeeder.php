<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an default admin user if it doesn't exist
        $user = User::firstOrCreate(
            [
                'username' => 'admin',
                'email' => 'admin@admin.com',
            ],
            [
                'password' => bcrypt('admin'),
                'user_level' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::firstOrCreate([
            'id' => $user->id,
        ], [
            'fullname' => 'System Administrator',
            'user_num_id' => '20250500001', // example ID
        ]);
    }
}
