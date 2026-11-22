@extends('layouts.print')
@section('title', 'Cetak Nota #' . $order->order_number)
@section('content')
<div class="nota-a4 p-4 border rounded-3 bg-white">
    <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
        <div>
            <h4 class="fw-bold mb-1">🎂 TOKO KUE LALA</h4>
            <div class="small">Jl. Matraman Dalam III No. 26B, Jakarta Timur</div>
            <div class="small">WhatsApp: {{ \App\Models\Setting::get('phone', '08123456789') }}</div>
        </div>
        <div class="text-end">
            <h5 class="fw-bold mb-0">NOTA PESANAN</h5>
            <div class="font-monospace fw-bold">#{{ $order->order_number }}</div>
            <div class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
        </div>
    </div>
    
    <div class="row g-2 mb-3 small">
        <div class="col-6">
            <div>Pelanggan: <strong>{{ $order->customer_name ?? $order->user?->name ?? 'Pelanggan' }}</strong></div>
            <div>WhatsApp: {{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
            @if($order->customer_address)
            <div>Alamat: {{ $order->customer_address }}</div>
            @endif
        </div>
        <div class="col-6 text-end">
            <div>Tgl Ambil: <strong>{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('d F Y') }}</strong></div>
            <div>Status: <span class="badge bg-secondary">{{ $order->status_label }}</span></div>
        </div>
    </div>
    
    <table class="table table-bordered table-sm small mb-3">
        <thead class="table-light">
            <tr>
                <th>Item Kue & Detail</th>
                <th class="text-center" style="width:50px;">Qty</th>
                <th class="text-end" style="width:110px;">Harga</th>
                <th class="text-end" style="width:110px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->size) <div>Ukuran: {{ $item->size }}</div> @endif
                    @if($item->cake_text) <div>Tulisan: "{{ $item->cake_text }}"</div> @endif
                    @if($item->custom_note) <div>Catatan: {{ $item->custom_note }}</div> @endif
                </td>
                <td class="text-center">{{ $item->qty }}</td>
                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">TOTAL:</th>
                <th class="text-end">Rp {{ number_format($order->total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    @if($order->note)
    <div class="small bg-light p-2 rounded mb-3">
        <strong>Catatan Tambahan:</strong> {{ $order->note }}
    </div>
    @endif
    
    <div class="text-center small text-muted pt-2 border-top">
        Terima kasih atas pesanan Anda di Toko Kue Lala!
    </div>
</div>
@endsection
