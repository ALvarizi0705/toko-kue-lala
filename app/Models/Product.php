<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'photo',
        'price',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Mengembalikan URL foto produk dari storage, atau URL placeholder jika foto tidak ada.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        return 'https://placehold.co/600x450/FFF8F0/5B3A29?text=' . urlencode($this->name);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Kategori produk.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Item-item dalam pesanan yang menyertakan produk ini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Item-item dalam penjualan langsung yang menyertakan produk ini.
     */
    public function directSaleItems(): HasMany
    {
        return $this->hasMany(DirectSaleItem::class);
    }
}
