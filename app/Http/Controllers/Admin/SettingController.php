<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'shop_name' => Setting::get('shop_name', 'Toko Kue Lala'),
            'address' => Setting::get('address', 'Jl. Matraman Dalam III No. 26B, Jakarta Timur'),
            'phone' => Setting::get('phone', '08123456789'),
            'pickup_hours' => Setting::get('pickup_hours', 'Senin - Sabtu, 08.00 - 20.00 WIB'),
            'bank_bca' => Setting::get('bank_bca', '1234567890 (a/n Lala Komalasari)'),
            'dana' => Setting::get('dana', '08123456789 (Toko Kue Lala)'),
            'gopay' => Setting::get('gopay', '08123456789 (Toko Kue Lala)'),
            'qris_image' => Setting::get('qris_image'),
            'min_order_days' => Setting::get('min_order_days', 1),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'pickup_hours' => 'required|string|max:255',
            'bank_bca' => 'nullable|string|max:255',
            'dana' => 'nullable|string|max:255',
            'gopay' => 'nullable|string|max:255',
            'min_order_days' => 'required|integer|min:0|max:30',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fields = ['shop_name', 'address', 'phone', 'pickup_hours', 'bank_bca', 'dana', 'gopay', 'min_order_days'];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field));
        }

        if ($request->hasFile('qris_image')) {
            $file = $request->file('qris_image');
            $fileName = 'qris_' . Str::random(16) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('settings', $fileName, 'public');
            Setting::set('qris_image', $path);
        }

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}
