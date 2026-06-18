<?php
namespace App\Services;

use App\Models\{Transaction, Stock, StockMovement};
use Illuminate\Support\Facades\DB;

class ReportService
{
    // Laporan transaksi periodik
    public function getTransactionReport(
        string $startDate, string $endDate, ?int $branchId = null
    ): array {
        $query = Transaction::with(['branch', 'cashier', 'items.product'])
            ->where('status', 'completed')
            ->whereBetween('transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->get();

        return [
            'transactions' => $transactions,
            'total'        => $transactions->sum('total_amount'),
            'count'        => $transactions->count(),
            'start_date'   => $startDate,
            'end_date'     => $endDate,
        ];
    }

    // Laporan stok saat ini
    public function getStockReport(?int $branchId = null): array
    {
        $query = Stock::with(['product.category', 'branch'])
            ->where('quantity', '>', 0);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return ['stocks' => $query->get()];
    }

    // Estimasi laba kotor
    public function getProfitReport(
        string $startDate, string $endDate, ?int $branchId = null
    ): array {
        $query = DB::table('transaction_items as ti')
            ->join('transactions as t', 'ti.transaction_id', '=', 't.id')
            ->join('products as p', 'ti.product_id', '=', 'p.id')
            ->where('t.status', 'completed')
            ->whereBetween('t.transaction_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select([
                'p.name as product_name',
                DB::raw('SUM(ti.quantity) as total_qty'),
                DB::raw('SUM(ti.subtotal) as total_revenue'),
                DB::raw('SUM(ti.quantity * p.buy_price) as total_cost'),
                DB::raw('SUM(ti.subtotal - (ti.quantity * p.buy_price)) as gross_profit'),
            ])
            ->groupBy('p.id', 'p.name');

        if ($branchId) {
            $query->where('t.branch_id', $branchId);
        }

        $data = $query->get();

        return [
            'items'          => $data,
            'total_revenue'  => $data->sum('total_revenue'),
            'total_cost'     => $data->sum('total_cost'),
            'total_profit'   => $data->sum('gross_profit'),
            'start_date'     => $startDate,
            'end_date'       => $endDate,
        ];
    }
}