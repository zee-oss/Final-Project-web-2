@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Laporan Laba Kotor</h2>

```
<div class="mb-3">
    <strong>Total Omzet:</strong>
    Rp {{ number_format($data['total_revenue'],0,',','.') }}
    <br>

    <strong>Total Modal:</strong>
    Rp {{ number_format($data['total_cost'],0,',','.') }}
    <br>

    <strong>Total Laba:</strong>
    Rp {{ number_format($data['total_profit'],0,',','.') }}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Qty Terjual</th>
            <th>Omzet</th>
            <th>Modal</th>
            <th>Laba Kotor</th>
        </tr>
    </thead>

    <tbody>
    @forelse($data['items'] as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->total_qty }}</td>
            <td>Rp {{ number_format($item->total_revenue,0,',','.') }}</td>
            <td>Rp {{ number_format($item->total_cost,0,',','.') }}</td>
            <td>Rp {{ number_format($item->gross_profit,0,',','.') }}</td>
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
