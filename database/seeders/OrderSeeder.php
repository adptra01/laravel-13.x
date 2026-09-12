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
use Throwable;

class OrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(
        private readonly InvoiceService $invoices,
        private readonly PaymentService $payments,
    ) {}

    /**
     * Membuat contoh pesanan:
     * - selesai + lunas + review
     * - dimasak + lunas
     * - menunggu + belum bayar
     *
     * API eksternal hanya digunakan untuk mengambil gambar bukti pembayaran.
     * Jika API gagal, proses seeding tetap berjalan.
     */
    public function run(): void
    {
        $customer = User::where('email', 'customer@testing.com')->first()
            ?? User::where('role', 'customer')->first();

        if (! $customer) {
            $this->command?->warn(
                'Customer belum di-seed — OrderSeeder dilewati.'
            );

            return;
        }

        $customerProfile = Customer::where('user_id', $customer->id)->first();

        $nusantara = Merchant::where(
            'company_name',
            'like',
            'Dapur Nusantara%'
        )->first();

        $sehatRasa = Merchant::where(
            'company_name',
            'like',
            'Sehat Rasa%'
        )->first();

        if (! $customerProfile || ! $nusantara) {
            $this->command?->warn(
                'Profil customer atau merchant Dapur Nusantara tidak ditemukan — OrderSeeder dilewati.'
            );

            return;
        }

        /*
         * Ambil menu yang tersedia.
         * Fallback ke menu pertama jika menu dengan nama tertentu tidak ditemukan.
         */
        $gorengan = Menu::where('merchant_id', $nusantara->id)
            ->where('name', 'like', '%Ayam Geprek%')
            ->first()
            ?? $nusantara->menus()->first();

        $rendang = Menu::where('merchant_id', $nusantara->id)
            ->where('name', 'like', '%Rendang%')
            ->first()
            ?? $gorengan;

        $salad = $sehatRasa?->menus()->first();

        /*
         * Jangan membuat order jika tidak ada menu.
         */
        if (! $gorengan) {
            $this->command?->warn(
                'Tidak ada menu pada merchant Dapur Nusantara — OrderSeeder dilewati.'
            );

            return;
        }

        /*
         * ---------------------------------------------------------
         * Pesanan 1: selesai + lunas + review
         * ---------------------------------------------------------
         */
        $selesai = $this->makeOrder(
            $customerProfile,
            $nusantara,
            [
                [$gorengan, 10],
                [$rendang ?? $gorengan, 5],
            ],
            now()->subDays(5),
            now()->subDays(4),
            'completed',
            'paid',
        );

        $this->paymentWithProof(
            $selesai,
            'bukti-transfer-selesai'
        );

        Review::create([
            'customer_id' => $customerProfile->id,
            'merchant_id' => $nusantara->id,
            'menu_id' => $gorengan->id,
            'order_id' => $selesai->id,
            'rating' => 5,
            'comment' => 'Ayam gepreknya pedas pas, porsi konsisten untuk 10 orang. Bakal order lagi minggu depan.',
        ]);

        /*
         * ---------------------------------------------------------
         * Pesanan 2: dimasak + lunas
         * ---------------------------------------------------------
         */
        $dimasak = $this->makeOrder(
            $customerProfile,
            $nusantara,
            [
                [$rendang ?? $gorengan, 8],
            ],
            now()->subDay(),
            now()->addDay(),
            'cooking',
            'paid',
        );

        $this->paymentWithProof(
            $dimasak,
            'bukti-transfer-dimasak'
        );

        /*
         * ---------------------------------------------------------
         * Pesanan 3: menunggu konfirmasi + belum bayar
         * ---------------------------------------------------------
         */
        if ($salad && $sehatRasa) {
            $this->makeOrder(
                $customerProfile,
                $sehatRasa,
                [
                    [$salad, 6],
                ],
                now(),
                now()->addDays(2),
                'pending',
                'unpaid',
            );
        }

        $this->command?->info(
            'Demo pesanan berhasil dibuat.'
        );
    }

    /**
     * Membuat order beserta item, invoice, dan payment.
     */
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
            /*
             * Safety check supaya Seeder tidak error
             * jika ada item menu yang null.
             */
            if (! $menu) {
                continue;
            }

            $line = $menu->price * $qty;
            $subtotal += $line;

            $rows[] = [
                $menu,
                $qty,
                $line,
            ];
        }

        if (empty($rows)) {
            throw new \RuntimeException(
                "Tidak ada menu valid untuk merchant {$merchant->company_name}."
            );
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

        /*
         * Invoice dan payment merupakan bagian dari
         * data aplikasi, jadi tidak bergantung pada API eksternal.
         */
        $this->invoices->createForOrder($order);
        $this->payments->createPayment($order);

        return $order;
    }

    /**
     * Menandai payment sebagai sukses dan mencoba
     * mengambil gambar bukti pembayaran.
     *
     * Jika API gagal:
     * - order tetap dibuat
     * - payment tetap sukses
     * - proof_path menjadi null
     */
    private function paymentWithProof($order, string $seed): void
    {
        $payment = $order->payments()->latest()->first()
            ?? $this->payments->createPayment($order);

        $proofPath = $this->fetchPaymentProof($seed);

        $payment->update([
            'status' => 'success',
            'proof_path' => $proofPath,
        ]);

        if ($proofPath) {
            $this->command?->info(
                "  ✓ Bukti pembayaran {$seed} berhasil disimpan."
            );
        } else {
            $this->command?->warn(
                "  ! Bukti pembayaran {$seed} tidak tersedia — seeding tetap dilanjutkan."
            );
        }
    }

    /**
     * Mengambil gambar bukti pembayaran dari public image API.
     *
     * DummyImage tidak membutuhkan API key.
     */
    private function fetchPaymentProof(string $seed): ?string
    {
        /*
         * Jangan melakukan HTTP request ketika testing.
         */
        if (app()->environment('testing')) {
            return null;
        }

        $path = "payment-proofs/{$seed}.png";

        $url = 'https://dummyimage.com/600x400/eeeeee/333333.png'
            .'?text='.urlencode('BUKTI PEMBAYARAN');

        return $this->fetchImage($url, $path);
    }

    /**
     * Download image dengan timeout, retry, validasi response,
     * dan exception handling.
     *
     * API error tidak akan menghentikan Seeder.
     */
    private function fetchImage(
        string $url,
        string $path,
    ): ?string {
        try {
            $response = Http::timeout(10)
                ->connectTimeout(5)
                ->retry(
                    2,
                    500,
                    throw: false
                )
                ->get($url);

            /*
             * HTTP 4xx / 5xx.
             */
            if (! $response->successful()) {
                $this->command?->warn(
                    "  ! API image mengembalikan HTTP {$response->status()} — dilewati."
                );

                return null;
            }

            /*
             * Pastikan response memang memiliki body.
             */
            $body = $response->body();

            if ($body === '') {
                $this->command?->warn(
                    '  ! API image mengembalikan data kosong — dilewati.'
                );

                return null;
            }

            /*
             * Simpan file ke storage/public.
             */
            $stored = Storage::disk('public')->put(
                $path,
                $body
            );

            if (! $stored) {
                $this->command?->warn(
                    "  ! Gagal menyimpan image ke {$path} — dilewati."
                );

                return null;
            }

            return $path;
        } catch (Throwable $e) {
            /*
             * Jangan biarkan kegagalan internet/API
             * menghentikan seluruh proses seeding.
             */
            $this->command?->warn(
                '  ! Gagal mengambil image dari API — seeding tetap dilanjutkan.'
            );

            return null;
        }
    }
}
