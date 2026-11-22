<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DirectSale extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sale_number',
        'user_id',
        'payment_method',
        'total',
        'sold_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total'   => 'decimal:2',
            'sold_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Kasir yang melakukan penjualan langsung ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Item-item produk dalam penjualan langsung ini.
     */
    public function directSaleItems(): HasMany
    {
        return $this->hasMany(DirectSaleItem::class);
    }

    public function items(): HasMany
    {
        return $this->directSaleItems();
    }
}
