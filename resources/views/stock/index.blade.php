@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Stok Barang</h2>

```
<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Cabang</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                <tr>
                    <td>{{ $stock->product->name ?? '-' }}</td>
                    <td>{{ $stock->branch->name ?? '-' }}</td>
                    <td>{{ $stock->quantity }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">
                        Data stok belum tersedia
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $stocks->links() }}

    </div>
</div>
```

</div>
@endsection
