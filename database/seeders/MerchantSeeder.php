<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MerchantSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Merchant demo beserta katalog menunya. Dua terverifikasi agar langsung
     * muncul di pencarian customer, satu pending untuk alur verifikasi admin.
     *
     * Gambar diunduh dari internet lalu disimpan ke disk public — setelah
     * `php artisan storage:link`, semua tampil lewat /storage/....
     */
    public function run(): void
    {
        $merchants = [
            [
                'user' => ['name' => 'Budi Santoso', 'email' => 'merchant1@testing.com'],
                'merchant' => [
                    'company_name' => 'Dapur Nusantara Catering',
                    'description' => 'Katering harian dengan menu Nusantara autentik, melayani kantor di area Jakarta.',
                    'address' => 'Jl. Gatot Subroto No. 12, Jakarta Selatan',
                    'phone' => '081298765432',
                    'verification_status' => 'verified',
                ],
                'logo_seed' => 'dapur-nusantara-logo',
                'banner_seed' => 'dapur-nusantara-banner',
                'menus' => [
                    ['category' => 'Nasi Box', 'name' => 'Nasi Box Ayam Geprek', 'price' => 25000, 'stock' => 100, 'description' => 'Ayam geprek sambal bawang, nasi hangat, lalapan, dan telur dadar.', 'seed' => 'menu-geprek'],
                    ['category' => 'Nasi Box', 'name' => 'Nasi Box Rendang', 'price' => 30000, 'stock' => 80, 'description' => 'Rendang sapi empuk santan pedas, nasi hangat, dan kerupuk merah.', 'seed' => 'menu-rendang'],
                    ['category' => 'Menu Mingguan', 'name' => 'Paket Langganan 5 Hari', 'price' => 120000, 'stock' => 50, 'description' => 'Lima hari makan siang dengan menu berganti tiap hari, hemat 20%.', 'seed' => 'menu-langganan'],
                ],
            ],
            [
                'user' => ['name' => 'Siti Rahma', 'email' => 'merchant2@testing.com'],
                'merchant' => [
                    'company_name' => 'Sehat Rasa Catering',
                    'description' => 'Katering sehat rendah kalori, cocok untuk program wellbeing karyawan.',
                    'address' => 'Jl. Rasuna Said No. 8, Jakarta Pusat',
                    'phone' => '081322233344',
                    'verification_status' => 'verified',
                ],
                'logo_seed' => 'sehat-rasa-logo',
                'banner_seed' => 'sehat-rasa-banner',
                'menus' => [
                    ['category' => 'Healthy Box', 'name' => 'Salad Bowl Ayam Panggang', 'price' => 28000, 'stock' => 60, 'description' => 'Ayam panggang, mix greens, quinoa, dan dressing lemon zesty.', 'seed' => 'menu-salad'],
                    ['category' => 'Healthy Box', 'name' => 'Nasi Merah Ikan Bakar', 'price' => 32000, 'stock' => 40, 'description' => 'Nasi merah, ikan kakap bakar, tumis buncis, dan sambal matah.', 'seed' => 'menu-ikan'],
                ],
            ],
            [
                'user' => ['name' => 'Andi Wijaya', 'email' => 'merchant3@testing.com'],
                'merchant' => [
                    'company_name' => 'Catering Prima (Menunggu Verifikasi)',
                    'description' => 'Katering event dan harian berkualitas.',
                    'address' => 'Jl. Thamrin No. 3, Jakarta Pusat',
                    'phone' => '081377788899',
                    'verification_status' => 'pending',
                ],
                'logo_seed' => 'catering-prima-logo',
                'banner_seed' => 'catering-prima-banner',
                'menus' => [
                    ['category' => 'Event', 'name' => 'Paket Rapat 50 Pax', 'price' => 1500000, 'stock' => 5, 'description' => 'Coffee break + lunch box untuk rapat 50 orang, termasuk alat makan.', 'seed' => 'menu-event'],
                ],
            ],
        ];

        foreach ($merchants as $data) {
            $user = User::create([
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'password' => Hash::make('password'),
                'role' => 'merchant',
                'email_verified_at' => now(),
            ]);

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
                'logo' => $this->fetchImage(
                    "https://picsum.photos/seed/{$data['logo_seed']}/300/300",
                    'logos/'.$data['logo_seed'].'.jpg'
                ),
                'banner' => $this->fetchImage(
                    "https://picsum.photos/seed/{$data['banner_seed']}/1200/400",
                    'banners/'.$data['banner_seed'].'.jpg'
                ),
            ]);

            foreach ($data['menus'] as $menu) {
                Menu::create([
                    'merchant_id' => $merchant->id,
                    'category' => $menu['category'],
                    'name' => $menu['name'],
                    'description' => $menu['description'],
                    'price' => $menu['price'],
                    'stock' => $menu['stock'],
                    'image' => $this->fetchImage(
                        "https://picsum.photos/seed/{$menu['seed']}/800/600",
                        'menus/'.$menu['seed'].'.jpg'
                    ),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Merchant: merchant1@testing.com & merchant2@testing.com (verified), merchant3@testing.com (pending) / password');
    }

    /**
     * Unduh gambar contoh dan simpan ke disk public. Gagal jaringan tidak
     * menggagalkan seeding — kolom dibiarkan null dan gambar fallback dipakai.
     */
    private function fetchImage(string $url, string $path): ?string
    {
        if (app()->environment('testing')) {
            return null;
        }

        try {
            $response = Http::timeout(20)->retry(2)->get($url);

            if ($response->failed()) {
                return null;
            }

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable $e) {
            $this->command?->warn("  ! Gagal mengunduh {$url} — dilewati.");

            return null;
        }
    }
}
