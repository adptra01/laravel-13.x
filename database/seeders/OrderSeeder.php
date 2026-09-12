<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Menu;
use App\Models\Merchant;
use App\Models\Review;
use App\Models\User;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(
        private readonly InvoiceService $invoices,
        private readonly PaymentService $payments,
    ) {}

    /**
     * Contoh pesanan yang sudah berjalan: selesai + lunas (dengan review),
     * dimasak + lunas, dan menunggu + belum bayar. Bukti pembayaran diunduh
     * dari internet dan disimpan ke disk public.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@testing.com')->first()
            ?? User::where('role', 'customer')->first();

        if (! $customer) {
            $this->command?->warn('Customer belum di-seed — DemoOrderSeeder dilewati.');

            return;
        }

        $customerProfile = Customer::where('user_id', $customer->id)->first();
        $nusantara = Merchant::where('company_name', 'like', 'Dapur Nusantara%')->first();
        $sehatRasa = Merchant::where('company_name', 'like', 'Sehat Rasa%')->first();

        if (! $customerProfile || ! $nusantara) {
            $this->command?->warn('Profil customer atau merchant Dapur Nusantara tidak ditemukan — dilewati.');

            return;
        }

        $gorengan = Menu::where('merchant_id', $nusantara->id)->where('name', 'like', 'Ayam Geprek')->first()
            ?? $nusantara->menus()->first();
        $rendang = $nusantara->menus()->where('name', 'like', 'Rendang')->first() ?? $gorengan;
        $salad = $sehatRasa?->menus()->first();

        // --- Pesanan 1: selesai + lunas + review ---
        $selesai = $this->makeOrder($customerProfile, $nusantara, [
            [$gorengan, 10],
            [$rendang, 5],
        ], now()->subDays(5), now()->subDays(4), 'completed', 'paid');

        $this->paymentWithProof($selesai, 'bukti-transfer-selesai');
        Review::create([
            'customer_id' => $customerProfile->id,
            'merchant_id' => $nusantara->id,
            'menu_id' => $gorengan->id,
            'order_id' => $selesai->id,
            'rating' => 5,
            'comment' => 'Ayam gepreknya pedas pas, porsi konsisten untuk 10 orang. Bakal order lagi minggu depan.',
        ]);

        // --- Pesanan 2: dimasak + lunas ---
        $dimasak = $this->makeOrder($customerProfile, $nusantara, [
            [$rendang, 8],
        ], now()->subDay(), now()->addDay(), 'cooking', 'paid');

        $this->paymentWithProof($dimasak, 'bukti-transfer-dimasak');

        // --- Pesanan 3: menunggu konfirmasi + belum bayar ---
        if ($salad) {
            $this->makeOrder($customerProfile, $sehatRasa, [
                [$salad, 6],
            ], now(), now()->addDays(2), 'pending', 'unpaid');
        }

        $this->command->info('Demo pesanan: 3 order (selesai+lunas+review, dimasak+lunas, menunggu+belum bayar)');
    }

    private function makeOrder(
        Customer $customer,
        Merchant $merchant,
        array $items,
        $orderDate,
        $deliveryDate,
        string $status,
        string $paymentStatus,
    ) {
        $subtotal = 0;
        $rows = [];

        foreach ($items as [$menu, $qty]) {
            $line = $menu->price * $qty;
            $subtotal += $line;
            $rows[] = [$menu, $qty, $line];
        }

        $order = $customer->orders()->create([
            'merchant_id' => $merchant->id,
            'order_date' => $orderDate->toDateString(),
            'delivery_date' => $deliveryDate->toDateString(),
            'total_price' => $subtotal,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'notes' => 'Antar ke lobi gedung, hubungi security.',
            'address' => $customer->address,
            'created_at' => $orderDate,
        ]);

        foreach ($rows as [$menu, $qty, $line]) {
            $order->items()->create([
                'menu_id' => $menu->id,
                'quantity' => $qty,
                'price' => $menu->price,
                'subtotal' => $line,
            ]);
        }

        $this->invoices->createForOrder($order);
        $this->payments->createPayment($order);

        return $order;
    }

    private function paymentWithProof($order, string $seed): void
    {
        $payment = $order->payments()->latest()->first()
            ?? $this->payments->createPayment($order);

        $payment->update([
            'status' => 'success',
            'proof_path' => $this->fetchImage(
                "https://picsum.photos/seed/{$seed}/600/400",
                'payment-proofs/'.$seed.'.jpg'
            ),
        ]);
    }

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
