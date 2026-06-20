<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Branch, Transaction, Stock, Product};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            return $this->ownerDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard($user->branch_id);
        } elseif ($user->isSupervisor()) {
            return $this->supervisorDashboard($user->branch_id);
        } else {
            return view('dashboard.cashier');
        }
    }

    private function ownerDashboard()
    {
        $todaySales = Transaction::where('status', 'completed')
            ->whereDate('transaction_date', today())
            ->sum('total_amount');

        $monthSales = Transaction::where('status', 'completed')
            ->whereMonth('transaction_date', now()->month)
            ->sum('total_amount');

        $criticalStocks = Stock::with(['product', 'branch'])
            ->whereColumn('quantity', '<', DB::raw('(SELECT min_stock FROM products WHERE id = stock.product_id)'))
            ->orderBy('quantity')
            ->limit(10)
            ->get();

        $salesByBranch = Transaction::where('status', 'completed')
            ->whereMonth('transaction_date', now()->month)
            ->select('branch_id', DB::raw('SUM(total_amount) as total'), DB::raw('COUNT(*) as count'))
            ->with('branch')
            ->groupBy('branch_id')
            ->get();

        $recentTransactions = Transaction::with(['branch', 'cashier'])
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        return view('dashboard.owner', compact(
            'todaySales', 'monthSales', 'criticalStocks',
            'salesByBranch', 'recentTransactions'
        ));
    }

    private function managerDashboard(int $branchId)
    {
        $branch = Branch::find($branchId);

        $todaySales = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereDate('transaction_date', today())
            ->sum('total_amount');

        $todayCount = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->whereDate('transaction_date', today())
            ->count();

        $criticalStocks = Stock::with('product')
            ->where('branch_id', $branchId)
            ->whereColumn('quantity', '<', DB::raw('(SELECT min_stock FROM products WHERE id = stock.product_id)'))
            ->get();

        $recentTransactions = Transaction::with('cashier')
            ->where('branch_id', $branchId)
            ->latest('transaction_date')
            ->limit(10)
            ->get();

        return view('dashboard.manager', compact(
            'branch', 'todaySales', 'todayCount',
            'criticalStocks', 'recentTransactions'
        ));
    }

    private function supervisorDashboard(int $branchId)
    {
        $branch = Branch::find($branchId);

        $todayTransactions = Transaction::with(['cashier', 'items'])
            ->where('branch_id', $branchId)
            ->whereDate('transaction_date', today())
            ->latest('transaction_date')
            ->get();

        $todayTotal = $todayTransactions->where('status', 'completed')->sum('total_amount');

        return view('dashboard.supervisor', compact('branch', 'todayTransactions', 'todayTotal'));
    }
}