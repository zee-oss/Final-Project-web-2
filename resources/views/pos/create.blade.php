@extends('layouts.app')
@section('title', 'Transaksi Baru')

@section('content')
<div class="row">
    {{-- Panel Produk --}}
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-search me-2"></i>Cari Produk</h5>
            </div>
            <div class="card-body">
                <input type="text" id="searchProduct" class="form-control form-control-lg mb-3"
                       placeholder="Ketik nama atau SKU produk...">
                <div id="productList" class="row g-2" style="max-height:500px;overflow-y:auto;">
                    @foreach($products as $product)
                    @php $stock = $product->stocks->first(); $qty = $stock ? $stock->quantity : 0; @endphp
                    <div class="col-6 product-card"
                         data-name="{{ strtolower($product->name) }}"
                         data-sku="{{ strtolower($product->sku) }}">
                        <div class="card h-100 {{ $qty <= 0 ? 'border-danger opacity-50' : 'border-0 shadow-sm' }}"
                             style="cursor:{{ $qty > 0 ? 'pointer' : 'not-allowed' }}"
                             onclick="{{ $qty > 0 ? 'addToCart('.$product->id.', \''.$product->name.'\', '.$product->sell_price.', '.$qty.')' : '' }}">
                            <div class="card-body p-2">
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <small class="text-muted">{{ $product->sku }}</small>
                                <div class="d-flex justify-content-between mt-1">
                                    <span class="text-success fw-bold">Rp {{ number_format($product->sell_price,0,',','.') }}</span>
                                    <span class="badge {{ $qty > $product->min_stock ? 'bg-success' : 'bg-warning text-dark' }}">
                                        Stok: {{ number_format($qty) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Keranjang --}}
    <div class="col-md-5">
        <form id="posForm" method="POST" action="{{ route('pos.store') }}">
            @csrf
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-cart me-2"></i>Keranjang Belanja</h5>
                </div>
                <div class="card-body p-0">
                    <div id="cartItems" style="min-height:200px;max-height:350px;overflow-y:auto;">
                        <div id="emptyCart" class="text-center text-muted p-4">
                            <i class="bi bi-cart-x fs-1"></i><br>Belum ada produk
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-2">
                        <span>TOTAL</span>
                        <span id="totalDisplay">Rp 0</span>
                    </div>
                    <input type="hidden" name="discount" value="0">
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Bayar (Rp)</label>
                        <input type="number" id="paidAmount" name="paid_amount" class="form-control form-control-lg"
                               min="0" step="1000" placeholder="0" oninput="calcChange()">
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Kembalian:</span>
                        <span id="changeDisplay" class="fw-bold text-primary">Rp 0</span>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-2"></i>Selesaikan Transaksi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let cart = {};
let cartTotal = 0;

function addToCart(id, name, price, maxStock) {
    if (cart[id]) {
        if (cart[id].qty >= maxStock) {
            alert('Stok tidak mencukupi!'); return;
        }
        cart[id].qty++;
    } else {
        cart[id] = { name, price, qty: 1, maxStock };
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyMsg  = document.getElementById('emptyCart');
    const submitBtn = document.getElementById('submitBtn');
    const form      = document.getElementById('posForm');

    // Hapus hidden inputs lama
    form.querySelectorAll('[name^="items"]').forEach(e => e.remove());

    if (Object.keys(cart).length === 0) {
        container.innerHTML = '<div id="emptyCart" class="text-center text-muted p-4"><i class="bi bi-cart-x fs-1"></i><br>Belum ada produk</div>';
        submitBtn.disabled = true;
        cartTotal = 0;
        document.getElementById('totalDisplay').textContent = 'Rp 0';
        return;
    }

    let html = '<table class="table table-sm mb-0"><tbody>';
    cartTotal = 0;
    let idx   = 0;

    for (const [id, item] of Object.entries(cart)) {
        const sub = item.qty * item.price;
        cartTotal += sub;
        html += `
        <tr>
            <td class="ps-3">
                <div class="fw-semibold small">${item.name}</div>
                <div class="text-muted small">@ Rp ${fmt(item.price)}</div>
            </td>
            <td class="text-center" style="width:100px">
                <div class="input-group input-group-sm">
                    <button class="btn btn-outline-secondary" onclick="changeQty(${id}, -1)">-</button>
                    <input type="number" class="form-control text-center" value="${item.qty}"
                           onchange="setQty(${id}, this.value)" min="1" max="${item.maxStock}">
                    <button class="btn btn-outline-secondary" onclick="changeQty(${id}, 1)">+</button>
                </div>
            </td>
            <td class="text-end pe-3">
                <div>Rp ${fmt(sub)}</div>
                <button class="btn btn-sm text-danger p-0" onclick="removeItem(${id})"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`;

        // Hidden inputs untuk form
        form.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="items[${idx}][product_id]" value="${id}">
            <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">
            <input type="hidden" name="items[${idx}][unit_price]" value="${item.price}">
        `);
        idx++;
    }

    html += '</tbody></table>';
    container.innerHTML = html;
    document.getElementById('totalDisplay').textContent = 'Rp ' + fmt(cartTotal);
    submitBtn.disabled = false;
    calcChange();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    const newQty = cart[id].qty + delta;
    if (newQty < 1) { removeItem(id); return; }
    if (newQty > cart[id].maxStock) { alert('Stok tidak mencukupi!'); return; }
    cart[id].qty = newQty;
    renderCart();
}

function setQty(id, val) {
    cart[id].qty = Math.min(Math.max(1, parseInt(val) || 1), cart[id].maxStock);
    renderCart();
}

function removeItem(id) { delete cart[id]; renderCart(); }

function calcChange() {
    const paid   = parseInt(document.getElementById('paidAmount').value) || 0;
    const change = paid - cartTotal;
    document.getElementById('changeDisplay').textContent = 'Rp ' + fmt(Math.max(0, change));
    document.getElementById('changeDisplay').className =
        change < 0 ? 'fw-bold text-danger' : 'fw-bold text-primary';
}

function fmt(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

// Filter produk
document.getElementById('searchProduct').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const match = card.dataset.name.includes(q) || card.dataset.sku.includes(q);
        card.style.display = match ? '' : 'none';
    });
});

// Konfirmasi sebelum submit
document.getElementById('posForm').addEventListener('submit', function(e) {
    const paid   = parseInt(document.getElementById('paidAmount').value) || 0;
    if (paid < cartTotal) {
        e.preventDefault();
        alert('Pembayaran kurang! Total: Rp ' + fmt(cartTotal));
    }
});
</script>
@endpush
@endsection