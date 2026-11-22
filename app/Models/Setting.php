<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * Primary key menggunakan kolom 'key' (string, non-incrementing).
     */
    protected $primaryKey = 'key';

    /**
     * Tipe primary key bukan integer, sehingga auto-increment dinonaktifkan.
     */
    public $incrementing = false;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
    ];

    // -------------------------------------------------------------------------
    // Static Helpers
    // -------------------------------------------------------------------------

    /**
     * Mengambil nilai setting berdasarkan key.
     *
     * @param  string  $key      Kunci setting.
     * @param  mixed   $default  Nilai default jika key tidak ditemukan.
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::find($key);

        return $setting ? $setting->value : $default;
    }

    /**
     * Menyimpan atau memperbarui nilai setting.
     *
     * @param  string  $key    Kunci setting.
     * @param  mixed   $value  Nilai yang akan disimpan.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }
}
