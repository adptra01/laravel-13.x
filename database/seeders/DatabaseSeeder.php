<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Admin ---
        $admin = User::create([
            'name' => 'Admin Platform',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->command->info('Admin: admin@example.com / password');

        // --- Customer ---
        $customerUser = $this->createUser('customer', 'PT Maju Jaya', 'customer@example.com');
        Customer::create([
            'user_id' => $customerUser->id,
            'company_name' => 'PT Maju Jaya',
            'slug' => Str::slug('PT Maju Jaya').'-'.Str::lower(Str::random(5)),
            'description' => 'Perusahaan manufaktur di Jakarta.',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
            'phone' => '081234567890',
        ]);

        // --- Merchants (verified agar langsung muncul di pencarian) ---
        $merchantData = [
            [
                'user' => ['name' => 'Budi Santoso', 'email' => 'merchant1@example.com'],
                'merchant' => [
                    'company_name' => 'Dapur Nusantara Catering',
                    'description' => 'Katering harian dengan menu Nusantara autentik, melayani kantor di area Jakarta.',
                    'address' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
                    'phone' => '081298765432',
                    'verification_status' => 'verified',
                ],
                'menus' => [
                    ['category' => 'Nasi Box', 'name' => 'Nasi Box Ayam Geprek', 'price' => 25000, 'stock' => 100],
                    ['category' => 'Nasi Box', 'name' => 'Nasi Box Rendang', 'price' => 30000, 'stock' => 80],
                    ['category' => 'Menu Mingguan', 'name' => 'Paket Langganan 5 Hari', 'price' => 120000, 'stock' => 50],
                ],
            ],
            [
                'user' => ['name' => 'Siti Rahma', 'email' => 'merchant2@example.com'],
                'merchant' => [
                    'company_name' => 'Sehat Rasa Catering',
                    'description' => 'Katering sehat rendah kalori, cocok untuk program wellbeing karyawan.',
                    'address' => 'Jl. Rasuna Said No. 8, Jakarta Pusat',
                    'phone' => '081322233344',
                    'verification_status' => 'verified',
                ],
                'menus' => [
                    ['category' => 'Healthy Box', 'name' => 'Salad Bowl Ayam', 'price' => 28000, 'stock' => 60],
                    ['category' => 'Healthy Box', 'name' => 'Nasi Merah Ikan Bakar', 'price' => 32000, 'stock' => 40],
                ],
            ],
            [
                'user' => ['name' => 'Andi Wijaya', 'email' => 'merchant3@example.com'],
                'merchant' => [
                    'company_name' => 'Catering Prima (Menunggu Verifikasi)',
                    'description' => 'Katering event dan harian berkualitas.',
                    'address' => 'Jl. Thamrin No. 3, Jakarta Pusat',
                    'phone' => '081377788899',
                    'verification_status' => 'pending',
                ],
                'menus' => [
                    ['category' => 'Event', 'name' => 'Paket Rapat 50 Pax', 'price' => 1500000, 'stock' => 5],
                ],
            ],
        ];

        foreach ($merchantData as $index => $data) {
            $user = $this->createUser('merchant', $data['user']['name'], $data['user']['email']);

            $merchant = Merchant::create([
                'user_id' => $user->id,
                'company_name' => $data['merchant']['company_name'],
                'slug' => Str::slug($data['merchant']['company_name']).'-'.Str::lower(Str::random(5)),
                'description' => $data['merchant']['description'],
                'address' => $data['merchant']['address'],
                'phone' => $data['merchant']['phone'],
                'verification_status' => $data['merchant']['verification_status'],
                'bank_name' => 'Bank BCA',
                'bank_account' => '1234567890',
            ]);

            foreach ($data['menus'] as $menu) {
                Menu::create([
                    'merchant_id' => $merchant->id,
                    'category' => $menu['category'],
                    'name' => $menu['name'],
                    'price' => $menu['price'],
                    'stock' => $menu['stock'],
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Merchant: merchant1@example.com & merchant2@example.com (verified), merchant3@example.com (pending) / password');
        $this->command->info('Customer: customer@example.com / password');
        $this->command->info('Semua akun: password = "password"');
    }

    private function createUser(string $role, string $name, string $email): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
            'email_verified_at' => now(),
        ]);
    }
}
