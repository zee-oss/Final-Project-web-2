<?php
namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\{Stock, Product, Branch};
use App\Repositories\StockRepository;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(protected StockRepository $stockRepo) {}

    // Lihat stok – semua role
    public function index(Request $request)
    {
        $user     = auth()->user();
        $branchId = $user->isOwner() ? $request->branch_id : $user->branch_id;

        $query = Stock::with(['product.category', 'branch']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->category_id) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $request->category_id));
        }

        $stocks     = $query->paginate(25);
        $branches   = Branch::where('is_active', true)->get();
        $categories = \App\Models\Category::all();

        return view('stock.index', compact('stocks', 'branches', 'categories', 'branchId'));
    }

    // Form opname
    public function opnameForm(Request $request)
    {
        $user     = auth()->user();
        $branchId = $user->isOwner() ? $request->branch_id : $user->branch_id;

        $stocks = Stock::with('product')
            ->where('branch_id', $branchId)
            ->get();

        return view('stock.opname', compact('stocks', 'branchId'));
    }

    // Proses opname
    public function processOpname(Request $request)
    {
        $request->validate([
            'branch_id'          => 'required|exists:branches,id',
            'adjustments'        => 'required|array',
            'adjustments.*.product_id' => 'required|exists:products,id',
            'adjustments.*.actual_qty' => 'required|numeric|min:0',
            'adjustments.*.notes'      => 'nullable|string',
        ]);

        foreach ($request->adjustments as $adj) {
            $stock = Stock::where('product_id', $adj['product_id'])
                ->where('branch_id', $request->branch_id)
                ->first();

            // Hanya update jika ada selisih
            if ($stock && $stock->quantity != $adj['actual_qty']) {
                $this->stockRepo->adjustStock(
                    $adj['product_id'],
                    $request->branch_id,
                    $adj['actual_qty'],
                    $adj['notes'] ?? 'Stok opname'
                );
            }
        }

        return redirect()->route('stock.index')->with('success', 'Stok opname berhasil disimpan.');
    }

    // API: ambil stok produk di cabang (untuk POS AJAX)
    public function getProductStock(Request $request, int $productId)
    {
        $branchId = auth()->user()->branch_id;
        $stock = $this->stockRepo->getStock($productId, $branchId);
        return response()->json(['quantity' => $stock->quantity]);
    }
}