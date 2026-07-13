<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'vizziodocs@gmail.com'],
            [
                'name' => 'Admin VizzioDocs',
                'password' => Hash::make('vizziodocsadmin2026'),
                'role' => 'admin',
                'daily_quota' => 999,
                'last_quota_reset' => now(),
            ]
        );

        $this->command->info('Admin account vizziodocs@gmail.com ready.');
    }
}
