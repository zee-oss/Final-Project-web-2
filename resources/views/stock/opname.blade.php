@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="mb-4">Stock Opname</h2>

```
<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('stock.opname.process') }}">
            @csrf

            <input type="hidden" name="branch_id" value="{{ $branchId }}">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Stok Sistem</th>
                        <th>Stok Aktual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stocks as $index => $stock)
                    <tr>
                        <td>
                            {{ $stock->product->name ?? '-' }}

                            <input type="hidden"
                                   name="adjustments[{{ $index }}][product_id]"
                                   value="{{ $stock->product_id }}">
                        </td>

                        <td>{{ $stock->quantity }}</td>

                        <td>
                            <input type="number"
                                   class="form-control"
                                   name="adjustments[{{ $index }}][actual_qty]"
                                   value="{{ $stock->quantity }}"
                                   step="0.001">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">
                Simpan Opname
            </button>
        </form>

    </div>
</div>
```

</div>
@endsection
