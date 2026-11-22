<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DirectSaleItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'direct_sale_id',
        'product_id',
        'product_name',
        'unit_price',
        'qty',
        'subtotal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal'   => 'decimal:2',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Penjualan langsung yang memiliki item ini.
     */
    public function directSale(): BelongsTo
    {
        return $this->belongsTo(DirectSale::class);
    }

    /**
     * Produk yang direferensikan oleh item ini.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
