<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createContext(): array
    {
        $merchantUser = User::factory()->create(['role' => 'merchant']);
        $merchant = Merchant::create([
            'user_id' => $merchantUser->id,
            'company_name' => 'Katering Uji',
            'slug' => 'katering-uji-'.Str::lower(Str::random(5)),
        ]);

        $customerUser = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $customerUser->id,
            'company_name' => 'Kantor Uji',
            'slug' => 'kantor-uji-'.Str::lower(Str::random(5)),
        ]);

        $menu = Menu::create([
            'merchant_id' => $merchant->id,
            'category' => 'Nasi Box',
            'name' => 'Nasi Uji',
            'price' => 25000,
            'stock' => 10,
            'is_active' => true,
        ]);

        return compact('merchantUser', 'merchant', 'customerUser', 'customer', 'menu');
    }

    private function createOrder(array $context, string $status, string $paymentStatus = 'unpaid'): Order
    {
        return Order::create([
            'customer_id' => $context['customer']->id,
            'merchant_id' => $context['merchant']->id,
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'total_price' => 25000,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'address' => 'Jl. Uji No. 1',
        ]);
    }

    public function test_next_status_follows_the_defined_flow(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');

        $this->assertSame('confirmed', $order->nextStatus());

        $order->update(['status' => 'completed']);
        $this->assertNull($order->nextStatus());

        $order->update(['status' => 'cancelled']);
        $this->assertNull($order->nextStatus());
    }

    public function test_merchant_can_advance_to_the_next_status_only(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');
        $this->actingAs($context['merchantUser']);

        $response = $this->patch(route('merchant.orders.status', $order), ['status' => 'confirmed']);

        $response->assertSessionHas('saved');
        $this->assertSame('confirmed', $order->fresh()->status);
    }

    public function test_merchant_cannot_skip_status_flow(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');
        $this->actingAs($context['merchantUser']);

        $response = $this->patch(route('merchant.orders.status', $order), ['status' => 'completed']);

        $response->assertSessionHas('error');
        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_merchant_cannot_update_a_finalized_order(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'completed');
        $this->actingAs($context['merchantUser']);

        $response = $this->patch(route('merchant.orders.status', $order), ['status' => 'confirmed']);

        $response->assertSessionHas('error');
        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_customer_cannot_cancel_a_paid_order(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending', 'paid');
        $this->actingAs($context['customerUser']);

        $response = $this->post(route('customer.orders.cancel', $order));

        $response->assertSessionHas('error');
        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_customer_can_cancel_an_unpaid_pending_order(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');
        $this->actingAs($context['customerUser']);

        $response = $this->post(route('customer.orders.cancel', $order));

        $response->assertSessionHas('saved');
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_customer_cannot_pay_a_paid_order_twice(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending', 'paid');
        $this->actingAs($context['customerUser']);

        $response = $this->post(route('customer.orders.pay', $order));

        $response->assertSessionHas('error');
        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertCount(0, $order->payments()->get());
    }

    public function test_customer_can_submit_payment_proof(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');
        $this->actingAs($context['customerUser']);

        $response = $this->post(route('customer.orders.pay', $order), [
            'proof' => File::fake()->image('bukti.jpg'),
        ]);

        $response->assertSessionHas('saved');
        $payment = $order->payments()->latest()->first();
        $this->assertNotNull($payment->proof_path);
        $this->assertSame('pending', $payment->status);
        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }

    public function test_merchant_can_confirm_payment_proof(): void
    {
        $context = $this->createContext();
        $order = $this->createOrder($context, 'pending');
        $payment = $order->payments()->create([
            'amount' => 25000,
            'status' => 'pending',
            'proof_path' => 'payment-proofs/bukti.jpg',
        ]);
        $this->actingAs($context['merchantUser']);

        $response = $this->post(route('merchant.payments.confirm', [$order, $payment]));

        $response->assertSessionHas('saved');
        $this->assertSame('success', $payment->fresh()->status);
        $this->assertSame('paid', $order->fresh()->payment_status);
    }
}
