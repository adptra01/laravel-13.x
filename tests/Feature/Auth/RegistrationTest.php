<?php

namespace Tests\Feature\Auth;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Daftar sebagai');
    }

    public function test_legacy_role_urls_redirect_to_the_global_form(): void
    {
        $this->get('/register/merchant')->assertRedirect(route('register', ['role' => 'merchant']));
        $this->get('/register/customer')->assertRedirect(route('register', ['role' => 'customer']));
    }

    public function test_new_customers_can_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'customer',
            'name' => 'Test User',
            'company_name' => 'PT Contoh Kantor',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard', absolute: false));
    }

    public function test_new_merchants_can_register_and_get_a_profile(): void
    {
        $response = $this->post('/register', [
            'role' => 'merchant',
            'name' => 'Budi Uji',
            'company_name' => 'Katering Uji Jaya',
            'email' => 'merchant-uji@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('merchant.dashboard', absolute: false));

        $user = User::where('email', 'merchant-uji@example.com')->first();
        $this->assertSame('merchant', $user->role);
        $this->assertTrue(Merchant::where('user_id', $user->id)->exists());
    }

    public function test_admin_role_cannot_self_register(): void
    {
        $response = $this->post('/register', [
            'role' => 'admin',
            'name' => 'Test Admin',
            'company_name' => 'PT Admin',
            'email' => 'admin-uji@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertGuest();
    }
}
