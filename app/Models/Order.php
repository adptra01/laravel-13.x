<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'customer_id',
    'merchant_id',
    'order_date',
    'delivery_date',
    'total_price',
    'status',
    'payment_status',
    'notes',
    'address',
])]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUS_FLOW = ['pending', 'confirmed', 'cooking', 'delivered', 'completed'];

    public const STATUS_LABELS = [
        'pending' => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'cooking' => 'Dimasak',
        'delivered' => 'Terkirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Status berikutnya yang sah menurut STATUS_FLOW, atau null jika
     * pesanan sudah final (selesai) atau di luar alur (dibatalkan).
     */
    public function nextStatus(): ?string
    {
        $index = array_search($this->status, self::STATUS_FLOW, true);

        if ($index === false) {
            return null;
        }

        return self::STATUS_FLOW[$index + 1] ?? null;
    }

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'delivery_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function latestInvoice(): HasOne
    {
        return $this->hasOne(Invoice::class)->latestOfMany();
    }
}
