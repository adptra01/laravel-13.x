<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Admin Platform',
            'email' => 'admin@testing.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->command->info('Admin: admin@testing.com / password');
    }
}
