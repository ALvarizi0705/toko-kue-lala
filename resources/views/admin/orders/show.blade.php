@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->order_number)
@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
        </a>
        <h4 class="fw-bold mb-0 text-choco">Pesanan #{{ $order->order_number }}</h4>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.pesanan.cetak', $order) }}" class="btn btn-outline-dark btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak Nota
        </a>
        @php
            $custPhone = preg_replace('/[^0-9]/', '', $order->customer_phone ?? $order->user?->phone ?? '');
            if (str_starts_with($custPhone, '0')) {
                $custPhone = '62' . substr($custPhone, 1);
            }
        @endphp
        @if($custPhone && in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
        <a href="https://wa.me/{{ $custPhone }}?text={{ urlencode('Halo kak ' . ($order->customer_name ?? $order->user?->name) . ', kami dari Toko Kue Lala mengenai pesanan #' . $order->order_number) }}" target="_blank" class="btn btn-success btn-sm">
            <i class="bi bi-whatsapp me-1"></i>Hubungi via WhatsApp
        </a>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- ITEM PESANAN & KUSTOMISASI --}}
        <div class="form-card mb-4">
            <h5 class="fw-bold text-choco mb-3">Item Kue & Kustomisasi</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kue & Kustomisasi</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="fw-bold text-choco">{{ $item->product_name }}</div>
                                @if($item->cake_text)
                                    <div class="small text-muted">Ucapan: <span class="badge bg-light text-dark border">"{{ $item->cake_text }}"</span></div>
                                @endif
                                @if($item->custom_note)
                                    <div class="small text-muted">Catatan: {{ $item->custom_note }}</div>
                                @endif
                            </td>
                            <td class="text-center fw-bold">{{ $item->qty }}</td>
                            <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Tagihan:</td>
                            <td class="text-end fw-bold fs-5" style="color:var(--caramel);">
                                Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            @if($order->note)
            <div class="p-3 bg-light rounded-3 mt-2">
                <div class="small fw-bold text-muted mb-1">Catatan Tambahan Pesanan:</div>
                <div class="small">{{ $order->note }}</div>
            </div>
            @endif
        </div>
        
        {{-- RIWAYAT PERUBAHAN STATUS --}}
        <div class="form-card">
            <h5 class="fw-bold text-choco mb-3"><i class="bi bi-clock-history me-2"></i>Log Riwayat Status</h5>
            <div class="timeline">
                @foreach($order->orderStatusLogs->sortByDesc('created_at') as $log)
                <div class="d-flex align-items-start gap-3 py-2 border-bottom">
                    <span class="badge-status badge-{{ $log->to_status }} mt-1">
                        {{ \App\Models\Order::getStatusLabelFor($log->to_status) }}
                    </span>
                    <div class="flex-grow-1">
                        <div class="small text-muted">
                            {{ $log->created_at->format('d/m/Y H:i') }} WIB 
                            @if($log->user) • Oleh: <strong>{{ $log->user->name }}</strong> @endif
                        </div>
                        @if($log->note)
                            <div class="small text-dark">{{ $log->note }}</div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        {{-- KONTROL STATUS PESANAN --}}
        <div class="form-card mb-4">
            <h5 class="fw-bold text-choco mb-3">Ubah Status Pesanan</h5>
            <div class="mb-3 text-center">
                <span class="badge-status badge-{{ $order->status }} fs-6">
                    {{ $order->status_label }}
                </span>
            </div>
            
            {{-- Tombol Perubahan Status Sesuai Urutan Alur --}}
            @if($order->status === 'menunggu_pembayaran')
                <div class="alert alert-warning small mb-3">
                    <i class="bi bi-clock me-1"></i>Pesanan baru masuk. Pembayaran dilakukan via WhatsApp.
                </div>
                @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
                <form method="POST" action="{{ route('admin.pesanan.status', $order) }}" class="mb-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="diproses">
                    <button type="submit" class="btn btn-primary w-100 fw-bold" onclick="return confirm('Tandai pembayaran telah dikonfirmasi dan teruskan ke dapur produksi?')">
                        <i class="bi bi-check2-circle me-1"></i>Konfirmasi Bayar & Proses
                    </button>
                </form>
                @endif
            @elseif($order->status === 'menunggu_verifikasi')
                <div class="alert alert-warning small mb-3">
                    <i class="bi bi-info-circle me-1"></i>Pesanan ini sedang menunggu verifikasi pembayaran.
                </div>
                @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
                <form method="POST" action="{{ route('admin.pesanan.status', $order) }}" class="mb-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="diproses">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i>Verifikasi & Proses
                    </button>
                </form>
                @endif
            @elseif($order->status === 'diproses')
                <form method="POST" action="{{ route('admin.pesanan.status', $order) }}" class="mb-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="siap_diambil">
                    <button type="submit" class="btn btn-info w-100 fw-bold text-white" onclick="return confirm('Kue sudah selesai dibuat dan siap diambil / dikirim?')">
                        <i class="bi bi-box2-heart me-1"></i>Ubah: Siap Diambil
                    </button>
                </form>
            @elseif($order->status === 'siap_diambil')
                <form method="POST" action="{{ route('admin.pesanan.status', $order) }}" class="mb-2">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="btn btn-success w-100 fw-bold" onclick="return confirm('Pesanan sudah diserahkan kepada pelanggan dan selesai?')">
                        <i class="bi bi-check-circle me-1"></i>Ubah: Selesai
                    </button>
                </form>
            @elseif($order->status === 'selesai')
                <div class="alert alert-success small mb-0 text-center">
                    <i class="bi bi-check2-all me-1"></i>Pesanan ini sudah selesai.
                </div>
            @elseif($order->status === 'dibatalkan')
                <div class="alert alert-danger small mb-0 text-center">
                    <i class="bi bi-x-circle me-1"></i>Pesanan ini telah dibatalkan.
                </div>
            @endif
            
            {{-- PEMBATALAN (Hanya Pemilik & Kasir) --}}
            @if(in_array(auth()->user()->role, ['pemilik', 'staf_kasir']) && !in_array($order->status, ['selesai', 'dibatalkan']))
            <hr>
            <button class="btn btn-outline-danger btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#cancelBox">
                <i class="bi bi-x-lg me-1"></i>Batalkan Pesanan Ini
            </button>
            <div class="collapse mt-2" id="cancelBox">
                <form method="POST" action="{{ route('admin.pesanan.batalkan', $order) }}">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <label class="form-label small fw-bold">Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea name="cancel_reason" class="form-control form-control-sm" rows="2" placeholder="Alasan pembatalan..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                        Konfirmasi Pembatalan
                    </button>
                </form>
            </div>
            @endif
        </div>
        
        {{-- INFORMASI PEMESAN --}}
        <div class="form-card mb-4">
            <h5 class="fw-bold text-choco mb-3">Informasi Pelanggan</h5>
            <div class="small mb-2">
                <span class="text-muted d-block">Nama Pemesan:</span>
                <strong>{{ $order->customer_name ?? $order->user?->name ?? '-' }}</strong>
            </div>
            <div class="small mb-2">
                <span class="text-muted d-block">WhatsApp:</span>
                <strong>{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</strong>
            </div>
            <div class="small mb-2">
                <span class="text-muted d-block">Tanggal Pengambilan / Kirim:</span>
                <strong>{{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('l, d F Y') }}</strong>
            </div>
            @if($order->customer_address)
            <div class="small mb-2">
                <span class="text-muted d-block">Alamat / Lokasi:</span>
                <strong>{{ $order->customer_address }}</strong>
            </div>
            @endif
            <div class="small mb-3">
                <span class="text-muted d-block">Metode Bayar:</span>
                <strong class="text-uppercase">{{ str_replace('_', ' ', $order->payment_method ?? 'WhatsApp') }}</strong>
            </div>
            
            @if($custPhone && in_array(auth()->user()->role, ['pemilik', 'staf_kasir']))
            <a href="https://wa.me/{{ $custPhone }}?text={{ urlencode('Halo kak ' . ($order->customer_name ?? $order->user?->name) . ', kami dari Toko Kue Lala...') }}" target="_blank" class="btn btn-success btn-sm w-100">
                <i class="bi bi-whatsapp me-1"></i>Chat WhatsApp Pemesan
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
