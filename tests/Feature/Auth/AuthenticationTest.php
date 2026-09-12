<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_customers_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard', absolute: false));
    }

    public function test_merchants_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'merchant']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('merchant.dashboard', absolute: false));
    }

    public function test_admins_are_redirected_to_their_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard', absolute: false));
    }

    public function test_stale_intended_url_from_another_role_does_not_hijack_the_redirect(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        // Skenario: session menyimpan tujuan basi ke area admin (mis. sesi
        // admin sebelumnya kedaluwarsa di /admin), lalu user login customer.
        $response = $this->withSession(['url.intended' => '/admin'])->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard', absolute: false));
    }

    public function test_valid_intended_url_within_the_same_role_is_honored(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->withSession(['url.intended' => '/customer/invoices'])->post('/login', [
            'email' => $customer->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/customer/invoices');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
