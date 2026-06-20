<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
.header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 15px; }
.header h2 { color: #1e3a5f; margin: 0; }
.meta { margin-bottom: 12px; }
.meta td { padding: 2px 8px 2px 0; }
table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
table.data th { background: #1e3a5f; color: white; padding: 6px 8px; text-align: left; }
table.data td { padding: 5px 8px; border-bottom: 1px solid #eee; }
table.data tr:nth-child(even) td { background: #f8f9fa; }
.cancelled td { color: #dc3545; text-decoration: line-through; }
.summary { margin-top: 15px; text-align: right; border-top: 2px solid #1e3a5f; padding-top: 8px; }
.summary .total { font-size: 14px; font-weight: bold; color: #1e3a5f; }
.footer { margin-top: 20px; font-size: 9px; color: #888; text-align: center; }
</style>
</head>
<body>
<div class="header">
<h2>LAPORAN TRANSAKSI PENJUALAN</h2>
<div>SiMart – Sistem Informasi Mini Market</div>
</div>
<table class="meta">
<tr>
<td><b>Periode</b></td>
<td>: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</td>
<td width="30"></td>
<td><b>Cabang</b></td>
<td>: {{ $branch ? $branch->name : 'Semua Cabang' }}</td>
</tr>
<tr>
<td><b>Total Transaksi</b></td>
<td>: {{ $data['count'] }} transaksi</td>
<td></td>
<td><b>Dicetak</b></td>
<td>: {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}</td>
</tr>
</table>
<table class="data">
<thead>
<tr>
<th>No</th>
<th>Invoice</th>
<th>Tanggal</th><th>Tanggal</th>
<th>Kasir</th>
<th>Cabang</th>
<th>Total</th>
<th>Status</th>
</tr>
</thead>
<tbody>
@foreach($data['transactions'] as $i => $t)
<tr class="{{ $t->status === 'cancelled' ? 'cancelled' : '' }}">
<td>{{ $i + 1 }}</td>
<td>{{ $t->invoice_number }}</td>
<td>{{ $t->transaction_date->format('d/m/Y H:i') }}</td>
<td>{{ $t->cashier->name }}</td>
<td>{{ $t->branch->name }}</td>
<td style="text-align:right">Rp {{ number_format($t->total_amount,0,',','.') }}</td>
<td>{{ $t->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}</td>
</tr>
@endforeach
</tbody>
</table>
<div class="summary">
<div>Total Penjualan (completed):
<span class="total">Rp {{ number_format($data['total'],0,',','.') }}</span>
</div>
</div>
<div class="footer">
Dokumen ini digenerate otomatis oleh sistem SiMart. {{ now()->format('d/m/Y H:i:s') }}
</div>
</body>
</html>