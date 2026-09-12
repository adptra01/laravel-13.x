<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan penting: admin & customer dulu, merchant beserta katalognya terakhir.
     */
    public function run(): void
    {
        $this->call([
            SiteSettingSeeder::class,
            AdminSeeder::class,
            CustomerSeeder::class,
            MerchantSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
