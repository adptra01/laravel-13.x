<?php

namespace App\Models;

use Database\Factories\MerchantFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'company_name',
    'slug',
    'description',
    'address',
    'phone',
    'logo',
    'banner',
    'bank_name',
    'bank_account',
    'verification_status',
    'rejection_reason',
])]
class Merchant extends Model
{
    /** @use HasFactory<MerchantFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'verification_status' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
