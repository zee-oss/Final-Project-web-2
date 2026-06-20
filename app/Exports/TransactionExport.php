<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\{FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class TransactionExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
public function __construct(protected array $data) {}
public function array(): array
{
return $this->data['transactions']->map(fn($t) => [
$t->invoice_number,
$t->transaction_date->format('d/m/Y H:i'),
$t->cashier->name,
$t->branch->name,
$t->total_amount,
$t->status === 'completed' ? 'Selesai' : 'Dibatalkan',
])->toArray();
} 
 public function headings(): array
{
return ['Invoice', 'Tanggal', 'Kasir', 'Cabang', 'Total (Rp)', 'Status'];
} 
public function title(): string { return 'Laporan Transaksi'; }
public function styles(Worksheet $sheet): array
{
return [
1 => ['font' => ['bold' => true], 'fill' => [
'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
'color' => ['argb' => 'FF1E3A5F'],
]],
];
}
}