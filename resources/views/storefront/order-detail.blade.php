@extends('layouts.storefront')
@section('title', 'Status Pesanan #' . $order->order_number)
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- HEADER ALERT --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4 text-center">
                <div class="mb-2">
                    @if($order->status === 'menunggu_pembayaran')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6">⏳ Menunggu Pembayaran & Konfirmasi</span>
                    @elseif($order->status === 'diproses')
                        <span class="badge bg-primary px-3 py-2 rounded-pill fs-6">👩‍🍳 Pesanan Sedang Diproses Dapur</span>
                    @elseif($order->status === 'siap_diambil')
                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill fs-6">🎂 Kue Siap Diambil di Toko</span>
                    @elseif($order->status === 'selesai')
                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6">✅ Pesanan Selesai Diambil</span>
                    @elseif($order->status === 'dibatalkan')
                        <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">❌ Pesanan Dibatalkan</span>
                    @endif
                </div>

                <h3 class="fw-bold text-choco mb-1">Pesanan #{{ $order->order_number }}</h3>
                <p class="text-muted small mb-3">Dibuat pada {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                
                {{-- STEPPER / TIMELINE PELACAKAN --}}
                <div class="py-3 px-2">
                    <div class="d-flex justify-content-between position-relative">
                        <div class="text-center" style="width:25%;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center {{ in_array($order->status, ['menunggu_pembayaran', 'menunggu_verifikasi', 'diproses', 'siap_diambil', 'selesai']) ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width:36px;height:36px;">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <div class="small fw-semibold mt-1" style="font-size:0.75rem;">Diterima</div>
                        </div>
                        <div class="text-center" style="width:25%;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center {{ in_array($order->status, ['diproses', 'siap_diambil', 'selesai']) ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width:36px;height:36px;">
                                <i class="bi bi-fire"></i>
                            </div>
                            <div class="small fw-semibold mt-1" style="font-size:0.75rem;">Diproses</div>
                        </div>
                        <div class="text-center" style="width:25%;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center {{ in_array($order->status, ['siap_diambil', 'selesai']) ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width:36px;height:36px;">
                                <i class="bi bi-gift"></i>
                            </div>
                            <div class="small fw-semibold mt-1" style="font-size:0.75rem;">Siap Diambil</div>
                        </div>
                        <div class="text-center" style="width:25%;">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center {{ $order->status === 'selesai' ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="width:36px;height:36px;">
                                <i class="bi bi-check-lg"></i>
                            </div>
                            <div class="small fw-semibold mt-1" style="font-size:0.75rem;">Selesai</div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL WHATSAPP PENJUAL --}}
                @php
                    $waText = "Halo Toko Kue Lala, saya ingin konfirmasi pesanan *" . $order->order_number . "* atas nama *" . ($order->customer_name ?? $order->user?->name) . "*. Total: Rp " . number_format($order->total, 0, ',', '.') . ". Mohon info untuk pembayaran / pengambilannya di toko ya.";
                    $waUrl = "https://wa.me/" . $cleanShopPhone . "?text=" . urlencode($waText);
                @endphp
                <div class="d-flex flex-wrap justify-content-center gap-2 mt-2">
                    <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm px-3 py-2 fw-semibold">
                        <i class="bi bi-whatsapp me-1"></i>Chat Penjual via WhatsApp
                    </a>
                    <a href="{{ route('order.nota', $order->order_number) }}" target="_blank" class="btn btn-outline-dark btn-sm px-3 py-2">
                        <i class="bi bi-printer me-1"></i>Cetak Nota Pesanan
                    </a>
                </div>
            </div>

            {{-- DETAIL PESANAN --}}
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                <h5 class="fw-bold text-choco mb-3">Informasi Pemesanan & Pengambilan</h5>
                
                <div class="row g-3 mb-3 small">
                    <div class="col-sm-6">
                        <div class="text-muted">Nama Pemesan:</div>
                        <div class="fw-bold">{{ $order->customer_name ?? $order->user?->name ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted">Nomor WhatsApp:</div>
                        <div class="fw-bold">{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted">Jadwal Pengambilan di Toko:</div>
                        <div class="fw-bold text-choco">{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('l, d F Y') }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted">Metode Pengambilan:</div>
                        <div class="fw-bold text-success">Ambil Sendiri di Toko (Self-Pickup)</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted">Lokasi Toko:</div>
                        <div class="fw-bold">Jl. Matraman Dalam III No. 26B, Jakarta Timur (08.00 – 20.00 WIB)</div>
                    </div>
                    @if($order->note)
                    <div class="col-12">
                        <div class="text-muted">Catatan Tambahan:</div>
                        <div class="fw-bold">{{ $order->note }}</div>
                    </div>
                    @endif
                </div>

                <hr class="my-3">

                <h6 class="fw-bold text-choco mb-3">Daftar Kue yang Dipesan</h6>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Kue</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold small text-choco">{{ $item->product_name }}</div>
                                    @if($item->cake_text)
                                        <div class="text-muted" style="font-size:0.75rem;">Ucapan: "{{ $item->cake_text }}"</div>
                                    @endif
                                    @if($item->custom_note)
                                        <div class="text-muted" style="font-size:0.75rem;">Catatan: {{ $item->custom_note }}</div>
                                    @endif
                                </td>
                                <td class="text-center small">{{ $item->qty }}</td>
                                <td class="text-end small">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold pt-3">Total Tagihan:</td>
                                <td class="text-end fw-bold fs-6 pt-3" style="color:var(--caramel);">
                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- CATATAN PELANGGAN --}}
            <div class="p-3 rounded-4 bg-white border small text-muted">
                <i class="bi bi-info-circle text-primary me-1"></i>
                Simpan nomor pesanan <strong>#{{ $order->order_number }}</strong> ini untuk memeriksa status pesananmu kapan saja di menu <a href="{{ route('order.track') }}" class="fw-bold text-decoration-none">Lacak Pesanan</a>.
            </div>
        </div>
    </div>
</div>
@endsection
