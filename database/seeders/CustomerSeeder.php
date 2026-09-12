<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::create([
            'name' => 'Rina Kusuma',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        Customer::create([
            'user_id' => $user->id,
            'company_name' => 'PT Maju Jaya',
            'slug' => Str::slug('PT Maju Jaya').'-'.Str::lower(Str::random(5)),
            'description' => 'Perusahaan manufaktur di Jakarta.',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'phone' => '081234567890',
        ]);

        $this->command->info('Customer: customer@example.com / password');
    }
}
