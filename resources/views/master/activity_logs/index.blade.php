@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Log Aktivitas</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Aktivitas</th>
            <th>Waktu</th>
        </tr>

        @forelse($logs as $log)
        <tr>
            <td>{{ $log->id }}</td>
            <td>{{ $log->user_id ?? '-' }}</td>
            <td>{{ $log->description ?? '-' }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4">Belum ada log aktivitas</td>
        </tr>
        @endforelse
    </table>
</div>
@endsection