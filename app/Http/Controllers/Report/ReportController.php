<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Exports\TransactionExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(protected ReportService $service) {}

    private function getBranchId(Request $request): ?int
    {
        $user = auth()->user();
        return $user->isOwner() ? $request->branch_id : $user->branch_id;
    }

    // Laporan transaksi – tampilkan di halaman
    public function transaction(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date   ?? now()->toDateString();
        $branchId  = $this->getBranchId($request);

        $data     = $this->service->getTransactionReport($startDate, $endDate, $branchId);
        $branches = auth()->user()->isOwner() ? \App\Models\Branch::all() : collect();

        return view('reports.transaction', compact('data', 'branches', 'startDate', 'endDate', 'branchId'));
    }

    // Laporan stok
    public function stock(Request $request)
    {
        $branchId = $this->getBranchId($request);
        $data     = $this->service->getStockReport($branchId);
        $branches = auth()->user()->isOwner() ? \App\Models\Branch::all() : collect();

        return view('reports.stock', compact('data', 'branches', 'branchId'));
    }

    // Laporan laba kotor
    public function profit(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date   ?? now()->toDateString();
        $branchId  = $this->getBranchId($request);

        $data     = $this->service->getProfitReport($startDate, $endDate, $branchId);
        $branches = auth()->user()->isOwner() ? \App\Models\Branch::all() : collect();

        return view('reports.profit', compact('data', 'branches', 'startDate', 'endDate', 'branchId'));
    }

    // Export PDF
    public function exportPdf(Request $request, string $type)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date',
            'branch_id'  => 'nullable|exists:branches,id',
        ]);

        $startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
        $endDate   = $request->end_date   ?? now()->toDateString();
        $branchId  = $this->getBranchId($request);

        $data = match ($type) {
            'transaction' => $this->service->getTransactionReport($startDate, $endDate, $branchId),
            'stock'       => $this->service->getStockReport($branchId),
            'profit'      => $this->service->getProfitReport($startDate, $endDate, $branchId),
            default       => abort(404),
        };

        $branch   = $branchId ? \App\Models\Branch::find($branchId) : null;
        $pdf      = Pdf::loadView("pdf.report_{$type}", compact('data', 'branch', 'startDate', 'endDate'))
                       ->setPaper('a4', 'portrait');

        return $pdf->download("laporan_{$type}_{$startDate}_sd_{$endDate}.pdf");
    }

    public function exportExcel(Request $request, string $type)
{
$startDate = $request->start_date ?? now()->startOfMonth()->toDateString();
$endDate = $request->end_date ?? now()->toDateString();
$branchId = $this->getBranchId($request);
$data = $this->service->getTransactionReport($startDate, $endDate, $branchId);
return Excel::download(
new TransactionExport($data),
"laporan_transaksi_{$startDate}_sd_{$endDate}.xlsx"
);
}
}