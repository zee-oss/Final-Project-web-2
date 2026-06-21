@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Detail Transaksi</h2>

```
<div class="card mb-3">
    <div class="card-body">
        <p><strong>Invoice:</strong> {{ $transaction->invoice_number }}</p>
        <p><strong>Tanggal:</strong> {{ $transaction->transaction_date }}</p>
        <p><strong>Cabang:</strong> {{ $transaction->branch->name ?? '-' }}</p>
        <p><strong>Kasir:</strong> {{ $transaction->cashier->name ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $transaction->status }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Daftar Produk
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->unit_price,0,',','.') }}</td>
                    <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h5 class="text-end">
            Total :
            Rp {{ number_format($transaction->total_amount,0,',','.') }}
        </h5>
    </div>
</div>
```

</div>
@endsection
