@extends('layouts.app')

@section('content')

<div class="container">
    <h2>Laporan Stok</h2>

```
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Cabang</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
    @forelse($data['stocks'] as $stock)
        <tr>
            <td>{{ $stock->product->name ?? '-' }}</td>
            <td>{{ $stock->branch->name ?? '-' }}</td>
            <td>{{ $stock->quantity }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="3">Tidak ada data</td>
        </tr>
    @endforelse
    </tbody>
</table>
```

</div>
@endsection
