<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'unit',
        'stock',
        'min_stock',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'stock'     => 'decimal:3',
            'min_stock' => 'decimal:3',
        ];
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Mengecek apakah stok bahan sudah di bawah batas minimum.
     */
    public function isLowStock(): bool
    {
        return (float) $this->stock <= (float) $this->min_stock;
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Riwayat stok masuk bahan ini.
     */
    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Riwayat penggunaan stok bahan ini.
     */
    public function stockUsages(): HasMany
    {
        return $this->hasMany(StockUsage::class);
    }

    /**
     * Riwayat penyesuaian stok bahan ini.
     */
    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }
}
