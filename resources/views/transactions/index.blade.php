@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Riwayat Transaksi</h2>

```
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Cabang</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->invoice_number }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>{{ $transaction->branch->name ?? '-' }}</td>
                    <td>{{ $transaction->cashier->name ?? '-' }}</td>
                    <td>Rp {{ number_format($transaction->total_amount,0,',','.') }}</td>
                    <td>{{ $transaction->status }}</td>
                    <td>
                        <a href="{{ route('transactions.show',$transaction->id) }}"
                           class="btn btn-sm btn-primary">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $transactions->links() }}
    </div>
</div>
```

</div>
@endsection
