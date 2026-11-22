/**
 * Toko Kue Lala - JavaScript Utama
 */

// Konfirmasi hapus/aksi berbahaya
function confirmAction(message) {
    return confirm(message || 'Apakah Anda yakin?');
}

// Format harga ke Rupiah
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(angka);
}

// Update total di halaman keranjang/checkout
function updateCartTotal() {
    let total = 0;
    document.querySelectorAll('[data-price]').forEach(el => {
        const price = parseFloat(el.dataset.price) || 0;
        const qty = parseInt(el.closest('tr, .cart-item')?.querySelector('[data-qty]')?.value || 1);
        total += price * qty;
    });
    const totalEl = document.getElementById('cart-total');
    if (totalEl) totalEl.textContent = formatRupiah(total);
}

// Preview foto sebelum upload
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Validasi tanggal ambil minimal H+1
document.addEventListener('DOMContentLoaded', () => {
    const pickupDateInput = document.getElementById('pickup_date');
    if (pickupDateInput) {
        // Set min date dari server via data attribute, atau default H+1
        const minDays = parseInt(pickupDateInput.dataset.minDays || '1');
        const minDate = new Date();
        minDate.setDate(minDate.getDate() + minDays);
        const minDateStr = minDate.toISOString().split('T')[0];
        pickupDateInput.min = minDateStr;
        
        pickupDateInput.addEventListener('change', function() {
            if (this.value < minDateStr) {
                this.value = minDateStr;
                alert('Tanggal ambil minimal ' + minDays + ' hari dari sekarang (H+' + minDays + ').');
            }
        });
    }
    
    // Hitung subtotal per item di checkout
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('tr, .checkout-item');
            const price = parseFloat(row?.dataset.price || 0);
            const subtotalEl = row?.querySelector('.item-subtotal');
            if (subtotalEl) subtotalEl.textContent = formatRupiah(price * parseInt(this.value || 1));
            updateGrandTotal();
        });
    });
});

// Hitung total keseluruhan di checkout
function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.checkout-item').forEach(row => {
        const price = parseFloat(row.dataset.price || 0);
        const qty = parseInt(row.querySelector('.qty-input')?.value || 1);
        total += price * qty;
    });
    const grandTotalEl = document.getElementById('grand-total');
    if (grandTotalEl) grandTotalEl.textContent = formatRupiah(total);
}
