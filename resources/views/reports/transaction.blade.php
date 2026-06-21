@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Laporan Transaksi</h2>

```
<div class="mb-3">
    <strong>Total Transaksi:</strong> {{ $data['count'] }} <br>
    <strong>Total Penjualan:</strong>
    Rp {{ number_format($data['total'],0,',','.') }}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Invoice</th>
            <th>Kasir</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
    @forelse($data['transactions'] as $trx)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $trx->transaction_date }}</td>
            <td>{{ $trx->invoice_number }}</td>
            <td>{{ $trx->cashier->name ?? '-' }}</td>
            <td>Rp {{ number_format($trx->total_amount,0,',','.') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">
                Tidak ada data
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
```

</div>
@endsection
