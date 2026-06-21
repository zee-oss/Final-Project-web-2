@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Penerimaan Barang</h2>

    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Nomor Receiving</th>
            <th>Tanggal</th>
        </tr>

        @forelse($receivings as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->receiving_number }}</td>
            <td>{{ $item->receiving_date }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3">Belum ada data</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection