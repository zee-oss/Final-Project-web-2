<?php
namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\{Transaction, Product, Stock};
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $service) {}

    // Halaman POS untuk kasir
    public function create()
    {
        $branchId = auth()->user()->branch_id;
        // Ambil produk yang masih ada stoknya di cabang ini
        $products = Product::with(['category', 'stocks' => fn($q) => $q->where('branch_id', $branchId)])
            ->where('is_active', true)
            ->get();

        return view('pos.create', compact('products', 'branchId'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:0.001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'paid_amount'        => 'required|numeric|min:0',
        ]);

        try {
            $transaction = $this->service->createTransaction($request->only('items', 'paid_amount', 'discount'));
            return redirect()->route('pos.receipt', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // Struk transaksi
    public function receipt(int $id)
    {
        $transaction = Transaction::with(['items.product', 'branch', 'cashier'])->findOrFail($id);
        return view('pos.receipt', compact('transaction'));
    }

    // Daftar transaksi (untuk supervisor, manager, owner)
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Transaction::with(['branch', 'cashier']);

        // Filter cabang sesuai role
        if (!$user->isOwner()) {
            $query->where('branch_id', $user->branch_id);
        } elseif ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter tanggal
        if ($request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest('transaction_date')->paginate(20);
        $branches     = $user->isOwner() ? \App\Models\Branch::where('is_active', true)->get() : collect();

        return view('transactions.index', compact('transactions', 'branches'));
    }

    // Detail transaksi
    public function show(int $id)
    {
        $transaction = Transaction::with(['items.product', 'branch', 'cashier', 'cancelledBy'])
            ->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    // Batalkan transaksi (supervisor only)
    public function cancel(Request $request, int $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $transaction = Transaction::findOrFail($id);

        if ($transaction->status === 'cancelled') {
            return back()->withErrors(['error' => 'Transaksi sudah dibatalkan.']);
        }

        // Hanya boleh batalkan transaksi hari ini
        if ($transaction->transaction_date->toDateString() !== today()->toDateString()) {
            return back()->withErrors(['error' => 'Pembatalan hanya berlaku untuk transaksi hari ini.']);
        }

        $this->service->cancelTransaction($transaction, $request->reason);
        return back()->with('success', 'Transaksi berhasil dibatalkan.');
    }
}