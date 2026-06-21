<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Master\{BranchController, ProductController, CategoryController, SupplierController, UserController};
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Stock\StockController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ─────────────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/', fn() => redirect()->route('login'));

// ── Authenticated Routes ───────────────────────────────────────────────
Route::middleware(['auth', 'branch.access', 'activity.log'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword']);

    // Dashboard – semua role, tampilan berbeda per role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Owner Only ──────────────────────────────────────────────────
    Route::middleware('role:owner')->prefix('master')->name('master.')->group(function () {
        Route::resource('users',    UserController::class);
        Route::resource('branches', BranchController::class);
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // ── Owner + Manager ─────────────────────────────────────────────
    Route::middleware('role:owner,manager')->group(function () {
        Route::prefix('master')->name('master.')->group(function () {
            Route::resource('products',   ProductController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('suppliers',  SupplierController::class);
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('transactions',      [ReportController::class, 'transaction'])->name('transaction');
            Route::get('stock',             [ReportController::class, 'stock'])->name('stock');
            Route::get('movements',         [ReportController::class, 'movement'])->name('movement');
            Route::get('profit',            [ReportController::class, 'profit'])->name('profit');
            Route::get('export/pdf/{type}', [ReportController::class, 'exportPdf'])->name('export.pdf');
            Route::get('export/excel/{type}', [ReportController::class, 'exportExcel'])->name('export.excel');
        });
    });

    // ── Supervisor + Owner + Manager: lihat & batalkan transaksi ────
    Route::middleware('role:owner,manager,supervisor')->group(function () {
        Route::get('transactions',          [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{id}',     [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('transactions/{id}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

        // Stok opname
        Route::get('stock/opname',          [StockController::class, 'opnameForm'])->name('stock.opname');
        Route::post('stock/opname',         [StockController::class, 'processOpname'])->name('stock.opname.process');
    });

    // ── Kasir: buat transaksi ────────────────────────────────────────
    Route::middleware('role:cashier')->group(function () {
        Route::get('pos',             [TransactionController::class, 'create'])->name('pos.create');
        Route::post('pos',            [TransactionController::class, 'store'])->name('pos.store');
        Route::get('pos/receipt/{id}',[TransactionController::class, 'receipt'])->name('pos.receipt');
    });

    // ── Pegawai Gudang: penerimaan barang ────────────────────────────
    // Route::middleware('role:warehouse,supervisor,owner,manager')->group(function () {
    //     Route::resource('stock/receivings', StockReceivingController::class)
    //          ->names('stock.receivings');
    // });

    // ── Semua role: lihat stok ────────────────────────────────────────
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');

    // AJAX: cari produk untuk POS
    Route::get('api/products/search', [ProductController::class, 'search'])->name('api.products.search');
    Route::get('api/products/{id}/stock', [StockController::class, 'getProductStock'])->name('api.stock');
});