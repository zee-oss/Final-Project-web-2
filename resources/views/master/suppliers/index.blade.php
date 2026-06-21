@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Supplier</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Supplier</th>
            <th>Kontak</th>
            <th>Telepon</th>
        </tr>

        @forelse($suppliers as $supplier)
        <tr>
            <td>{{ $supplier->id }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->contact_name }}</td>
            <td>{{ $supplier->phone }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Tidak ada data</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection