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
                            <select name="payment_method" class="form-select" required>
                                <option value="tunai">Tunai (Cash)</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS Statis Toko</option>
                            </select>
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

function addRow() {
    const tbody = document.querySelector('#itemsTable tbody');
    const firstRow = tbody.querySelector('tr');
    const newRow = firstRow.cloneNode(true);
    
    newRow.dataset.price = "0";
    newRow.querySelector('.product-select').name = `items[${rowIndex}][product_id]`;
    newRow.querySelector('.product-select').selectedIndex = 0;
    newRow.querySelector('.qty-input').name = `items[${rowIndex}][qty]`;
    newRow.querySelector('.qty-input').value = 1;
    newRow.querySelector('.unit-price-display').textContent = '0';
    newRow.querySelector('.subtotal-display').textContent = '0';
    
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
</script>
@endpush
@endsection
