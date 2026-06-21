@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Cabang</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Cabang</th>
        </tr>

        @forelse($branches as $branch)
        <tr>
            <td>{{ $branch->id }}</td>
            <td>{{ $branch->name ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2">Tidak ada data</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection