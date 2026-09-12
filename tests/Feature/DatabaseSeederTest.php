<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\MerchantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_all_demo_accounts_and_catalog(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(5, User::count());
        $this->assertSame(1, User::where('role', 'admin')->count());
        $this->assertSame(1, User::where('role', 'customer')->count());
        $this->assertSame(3, User::where('role', 'merchant')->count());

        $this->assertSame(1, Customer::count());
        $this->assertSame(3, Merchant::count());
        $this->assertSame(2, Merchant::where('verification_status', 'verified')->count());
        $this->assertSame(1, Merchant::where('verification_status', 'pending')->count());
        $this->assertSame(6, Menu::count());
        $this->assertSame(1, Setting::count());
        $this->assertSame('KateringKu', Setting::first()->site_name);
    }

    public function test_individual_seeders_can_run_standalone(): void
    {
        $this->seed(AdminSeeder::class);
        $this->seed(MerchantSeeder::class);

        $this->assertSame(1, User::where('role', 'admin')->count());
        $this->assertSame(3, Merchant::count());
        $this->assertSame(0, Customer::count());
    }

    public function test_demo_orders_are_seeded_with_payments(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(3, Order::count());
        $this->assertSame(2, Payment::where('status', 'success')->count());
        $this->assertSame(1, Payment::where('status', 'pending')->count());
        $this->assertSame(3, Invoice::count());
        $this->assertSame(1, Review::count());
    }
}
