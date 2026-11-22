<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active'         => 'boolean',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Pesanan yang dibuat oleh user (sebagai pelanggan).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Penjualan langsung yang dicatat oleh user (sebagai kasir).
     */
    public function directSales(): HasMany
    {
        return $this->hasMany(DirectSale::class);
    }

    /**
     * Stok masuk yang dicatat oleh user.
     */
    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    /**
     * Penggunaan stok yang dicatat oleh user.
     */
    public function stockUsages(): HasMany
    {
        return $this->hasMany(StockUsage::class);
    }
}
