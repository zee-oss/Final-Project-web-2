@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Produk</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
        </tr>

        @forelse($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2">Tidak ada data</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection