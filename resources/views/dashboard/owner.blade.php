{{-- resources/views/dashboard/owner.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Owner')

@section('content')
<h4 class="mb-4">📊 Dashboard Owner – Semua Cabang</h4>

{{-- Kartu ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat border-success shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Penjualan Hari Ini</div>
                <div class="fw-bold fs-4 text-success">Rp {{ number_format($todaySales,0,',','.') }}</div>
                <i class="bi bi-cash-stack text-success opacity-25 float-end fs-1 mt-n3"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-primary shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Penjualan Bulan Ini</div>
                <div class="fw-bold fs-4 text-primary">Rp {{ number_format($monthSales,0,',','.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-danger shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Produk Stok Kritis</div>
                <div class="fw-bold fs-4 text-danger">{{ $criticalStocks->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat border-info shadow-sm">
            <div class="card-body">
                <div class="text-muted small">Total Cabang Aktif</div>
                <div class="fw-bold fs-4 text-info">{{ $salesByBranch->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Penjualan per cabang --}}
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">💰 Penjualan per Cabang – Bulan Ini</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Cabang</th><th>Transaksi</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @foreach($salesByBranch as $s)
                        <tr>
                            <td>{{ $s->branch->name }}</td>
                            <td>{{ number_format($s->count) }}</td>
                            <td class="fw-semibold">Rp {{ number_format($s->total,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Stok kritis --}}
    <div class="col-md-5">
        <div class="card shadow-sm border-danger">
            <div class="card-header text-danger">⚠️ Stok Kritis</div>
            <div class="card-body p-0">
                <table class="table mb-0 table-sm">
                    <thead class="table-light">
                        <tr><th>Produk</th><th>Cabang</th><th>Stok</th></tr>
                    </thead>
                    <tbody>
                        @foreach($criticalStocks as $s)
                        <tr>
                            <td>{{ $s->product->name }}</td>
                            <td><small>{{ $s->branch->code }}</small></td>
                            <td><span class="badge bg-danger">{{ $s->quantity }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Transaksi terbaru --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">🕐 5 Transaksi Terbaru (Semua Cabang)</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr><th>Invoice</th><th>Cabang</th><th>Kasir</th><th>Total</th><th>Waktu</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $t)
                        <tr>
                            <td><a href="{{ route('transactions.show', $t->id) }}">{{ $t->invoice_number }}</a></td>
                            <td>{{ $t->branch->name }}</td>
                            <td>{{ $t->cashier->name }}</td>
                            <td>Rp {{ number_format($t->total_amount,0,',','.') }}</td>
                            <td>{{ $t->transaction_date->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection