@extends('layouts.print')
@section('title', 'Nota Penjualan #' . $sale->sale_number)
@section('content')

@if($format === '58mm')
{{-- STRUK KERTAS KASIR THERMAL 58 MM --}}
<div class="struk-58mm mx-auto" style="width:58mm;font-family:monospace;font-size:11px;color:#000;">
    <div class="text-center pb-2 border-bottom">
        <div style="font-size:14px;font-weight:bold;">TOKO KUE LALA</div>
        <div style="font-size:10px;">Jl. Matraman Dalam III No. 26B</div>
        <div style="font-size:10px;">Jakarta Timur</div>
        <div style="font-size:10px;">WA: {{ \App\Models\Setting::get('phone', '08123456789') }}</div>
    </div>
    
    <div class="py-2 border-bottom" style="font-size:10px;">
        <div>No: #{{ $sale->sale_number }}</div>
        <div>Tgl: {{ \Carbon\Carbon::parse($sale->sold_at)->format('d/m/y H:i') }}</div>
        <div>Kasir: {{ $sale->user->name ?? 'Kasir' }}</div>
    </div>
    
    <div class="py-2 border-bottom">
        @foreach($sale->directSaleItems as $item)
        <div class="d-flex justify-content-between">
            <span style="max-width:35mm;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->product_name }}</span>
            <span>{{ $item->qty }}x</span>
        </div>
        <div class="d-flex justify-content-between" style="font-size:10px;">
            <span>@ {{ number_format($item->unit_price, 0, ',', '.') }}</span>
            <span>{{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>
    
    <div class="py-2 border-bottom">
        <div class="d-flex justify-content-between" style="font-weight:bold;font-size:12px;">
            <span>TOTAL</span>
            <span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between" style="font-size:10px;">
            <span>BAYAR ({{ strtoupper($sale->payment_method) }})</span>
            <span>Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="text-center pt-3" style="font-size:10px;">
        <div>Terima kasih atas kunjungannya!</div>
        <div>Kue Segar Dibuat Setiap Hari</div>
    </div>
</div>

@else

{{-- NOTA KERTAS A5/A4 STANDAR --}}
<div class="nota-a4 p-4 border rounded-3 bg-white">
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
        <div>
            <h4 class="fw-bold mb-1">🎂 TOKO KUE LALA</h4>
            <div class="small">Jl. Matraman Dalam III No. 26B, Jakarta Timur</div>
            <div class="small">WhatsApp: {{ \App\Models\Setting::get('phone', '08123456789') }}</div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0">NOTA PENJUALAN LANGSUNG</h5>
            <div class="font-monospace fw-bold">#{{ $sale->sale_number }}</div>
            <div class="small text-muted">{{ \Carbon\Carbon::parse($sale->sold_at)->format('d/m/Y H:i') }} WIB</div>
            <div class="small">Kasir: {{ $sale->user->name ?? 'Kasir' }}</div>
        </div>
    </div>
    
    <table class="table table-bordered table-sm small mb-3">
        <thead class="table-light">
            <tr>
                <th>Produk Kue</th>
                <th class="text-center" style="width:60px;">Qty</th>
                <th class="text-end" style="width:120px;">Harga Satuan</th>
                <th class="text-end" style="width:130px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->directSaleItems as $item)
            <tr>
                <td><strong>{{ $item->product_name }}</strong></td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">TOTAL:</th>
                <th class="text-end">Rp {{ number_format($sale->total, 0, ',', '.') }}</th>
            </tr>
            <tr>
                <th colspan="3" class="text-end font-weight-normal">Metode Bayar:</th>
                <th class="text-end text-uppercase">{{ $sale->payment_method }} (LUNAS)</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="text-center small text-muted pt-3 border-top">
        Terima kasih telah berbelanja di Toko Kue Lala!
    </div>
</div>
@endif

@endsection
