@extends('layouts.admin')
@section('title', 'Transaksi Penjualan Langsung')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-10">
        <form method="POST" action="{{ route('admin.penjualan-langsung.store') }}" id="posForm">
            @csrf
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-choco"><i class="bi bi-cart-check me-2"></i>Transaksi Penjualan Kasir (Toko)</h5>
                <a href="{{ route('admin.penjualan-langsung.index') }}" class="btn btn-sm btn-outline-secondary">Kembali</a>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="form-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-choco">Daftar Item Kue Terjual</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Baris
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table align-middle" id="itemsTable">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Produk Kue</th>
                                        <th style="width:120px;">Qty</th>
                                        <th style="width:140px;">Harga (Rp)</th>
                                        <th style="width:140px;">Subtotal (Rp)</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_id]" class="form-select form-select-sm product-select" required onchange="onProductChange(this)">
                                                <option value="">-- Pilih Kue --</option>
                                                @foreach($products as $prod)
                                                <option value="{{ $prod->id }}" data-price="{{ $prod->price }}">{{ $prod->name }} - Rp {{ number_format($prod->price, 0, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][qty]" class="form-control form-control-sm text-center qty-input" value="1" min="1" required oninput="calculateRow(this)">
                                        </td>
                                        <td class="unit-price-display text-muted text-end small">0</td>
                                        <td class="subtotal-display fw-bold text-end" style="color:var(--caramel);">0</td>
                                        <td>
                                            <button type="button" class="btn btn-sm text-danger p-0" onclick="removeRow(this)">
                                                <i class="bi bi-x-circle fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="form-card">
                        <h6 class="fw-bold text-choco mb-3">Pembayaran Kasir</h6>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select" required id="paymentMethodSelect" onchange="onPaymentMethodChange(this)">
                                <option value="tunai">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS Statis Toko</option>
                            </select>
                        </div>

                        {{-- QRIS IMAGE (tampil hanya saat metode QRIS dipilih) --}}
                        <div id="qrisSection" class="mb-3 text-center p-3 border rounded-3 bg-white" style="display:none;">
                            <div class="fw-bold small mb-2 text-choco"><i class="bi bi-qr-code-scan me-1"></i>Scan QRIS Statis Toko Kue Lala</div>
                            @if(!empty($qrisImage))
                                <img src="{{ asset('storage/' . $qrisImage) }}" alt="QRIS Toko Kue Lala"
                                     style="max-width:200px; border-radius:8px;" class="shadow-sm border">
                            @else
                                <div class="d-inline-block p-2 bg-white border rounded-3 shadow-sm mb-2">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=00020101021126580016ID.CO.TOKOKUELALA.WWW01189360099999999999990208123456785204541153033605802ID5913TOKO+KUE+LALA6007JAKARTA6304A1B2" 
                                         alt="QRIS Toko Kue Lala" style="max-width:180px;height:auto;">
                                </div>
                                <div class="badge bg-light text-dark border mb-1">NMID: ID1020030040050</div>
                            @endif
                            <p class="small text-muted mt-2 mb-0">Tunjukkan barcode ini kepada pelanggan untuk discan via BCA, Mandiri, DANA, GoPay, OVO, ShopeePay.</p>
                            @if(empty($qrisImage))
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">(Ingin pakai QRIS toko asli? Upload gambarnya di menu <a href="{{ route('admin.pengaturan.index') }}">Pengaturan Toko</a>)</small>
                            @endif
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted small">Total Belanja:</span>
                                <span class="fs-4 fw-bold" id="grandTotalDisplay" style="color:var(--caramel);">Rp 0</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary-lala w-100 py-3 fw-bold fs-6">
                            <i class="bi bi-check2-circle me-1"></i>Simpan & Cetak Nota
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let rowIndex = 1;

function formatRupiah(amount) {
    return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
}

function onProductChange(select) {
    const row = select.closest('tr');
    const selectedOption = select.options[select.selectedIndex];
    const price = parseFloat(selectedOption.dataset.price) || 0;
    row.dataset.price = price;
    row.querySelector('.unit-price-display').textContent = formatRupiah(price);
    calculateRow(row.querySelector('.qty-input'));
}

function calculateRow(qtyInput) {
    const row = qtyInput.closest('tr');
    const price = parseFloat(row.dataset.price) || 0;
    const qty = parseInt(qtyInput.value) || 0;
    const subtotal = price * qty;
    row.querySelector('.subtotal-display').textContent = formatRupiah(subtotal);
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let grandTotal = 0;
    document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
        const price = parseFloat(row.dataset.price) || 0;
        const qty = parseInt(row.querySelector('.qty-input')?.value) || 0;
        grandTotal += price * qty;
    });
    document.getElementById('grandTotalDisplay').textContent = formatRupiah(grandTotal);
}

function onPaymentMethodChange(select) {
    const qrisSection = document.getElementById('qrisSection');
    if (!qrisSection) return;
    qrisSection.style.display = (select.value === 'qris') ? 'block' : 'none';
}

function addRow() {
    const tbody = document.querySelector('#itemsTable tbody');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    
    newRow.dataset.price = "0";
    newRow.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
    newRow.querySelector('.product-select').selectedIndex = 0;
    newRow.querySelector('.qty-input').name = `items[${rowIndex}][qty]`;
    newRow.querySelector('.qty-input').value = 1;
    newRow.querySelector('.unit-price-display').textContent = 'Rp 0';
    newRow.querySelector('.subtotal-display').textContent = 'Rp 0';
    
    tbody.appendChild(newRow);
    rowIndex++;
}

function removeRow(btn) {
    const tbody = document.querySelector('#itemsTable tbody');
    if (tbody.querySelectorAll('tr').length > 1) {
        btn.closest('tr').remove();
        calculateGrandTotal();
    } else {
        alert('Minimal harus ada 1 item produk.');
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.product-select').forEach(select => {
        if (select.value) {
            onProductChange(select);
        }
    });
    const pmSelect = document.getElementById('paymentMethodSelect');
    if (pmSelect) {
        onPaymentMethodChange(pmSelect);
    }
});
</script>
@endpush
@endsection
