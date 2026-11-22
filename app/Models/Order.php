<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // -------------------------------------------------------------------------
    // Status Constants
    // -------------------------------------------------------------------------

    public const STATUS_MENUNGGU_PEMBAYARAN  = 'menunggu_pembayaran';
    public const STATUS_MENUNGGU_VERIFIKASI  = 'menunggu_verifikasi';
    public const STATUS_DIPROSES             = 'diproses';
    public const STATUS_SIAP_DIAMBIL        = 'siap_diambil';
    public const STATUS_SELESAI              = 'selesai';
    public const STATUS_DIBATALKAN           = 'dibatalkan';

    /**
     * Semua nilai status yang valid.
     */
    public const STATUS = [
        self::STATUS_MENUNGGU_PEMBAYARAN,
        self::STATUS_MENUNGGU_VERIFIKASI,
        self::STATUS_DIPROSES,
        self::STATUS_SIAP_DIAMBIL,
        self::STATUS_SELESAI,
        self::STATUS_DIBATALKAN,
    ];

    /**
     * Transisi status yang diperbolehkan: [dari_status => [ke_status, ...]].
     */
    public const VALID_TRANSITIONS = [
        self::STATUS_MENUNGGU_PEMBAYARAN => [
            self::STATUS_MENUNGGU_VERIFIKASI,
            self::STATUS_DIPROSES,
            self::STATUS_DIBATALKAN,
        ],
        self::STATUS_MENUNGGU_VERIFIKASI => [
            self::STATUS_DIPROSES,
            self::STATUS_DIBATALKAN,
        ],
        self::STATUS_DIPROSES => [
            self::STATUS_SIAP_DIAMBIL,
            self::STATUS_DIBATALKAN,
        ],
        self::STATUS_SIAP_DIAMBIL => [
            self::STATUS_SELESAI,
        ],
        self::STATUS_SELESAI   => [],
        self::STATUS_DIBATALKAN => [],
    ];

    // -------------------------------------------------------------------------
    // Model Configuration
    // -------------------------------------------------------------------------

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'pickup_date',
        'note',
        'total',
        'status',
        'payment_method',
        'payment_proof',
        'payment_reject_reason',
        'verified_by',
        'verified_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total'       => 'decimal:2',
            'pickup_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Label status dalam Bahasa Indonesia.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusLabelFor($this->status);
    }

    public static function getStatusLabelFor(?string $status): string
    {
        return match ($status) {
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::STATUS_MENUNGGU_VERIFIKASI => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES            => 'Diproses',
            self::STATUS_SIAP_DIAMBIL       => 'Siap Diambil',
            self::STATUS_SELESAI             => 'Selesai',
            self::STATUS_DIBATALKAN          => 'Dibatalkan',
            default                          => ucfirst($status ?? '-'),
        };
    }

    /**
     * Warna Bootstrap badge berdasarkan status.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_MENUNGGU_PEMBAYARAN => 'secondary',
            self::STATUS_MENUNGGU_VERIFIKASI => 'warning',
            self::STATUS_DIPROSES            => 'primary',
            self::STATUS_SIAP_DIAMBIL       => 'info',
            self::STATUS_SELESAI             => 'success',
            self::STATUS_DIBATALKAN          => 'danger',
            default                          => 'secondary',
        };
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * User (pelanggan) yang membuat pesanan ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Item-item produk dalam pesanan ini.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Log perubahan status pesanan.
     */
    public function orderStatusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    /**
     * User yang memverifikasi pesanan ini.
     */
    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
