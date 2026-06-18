<?php
namespace App\Services;

use App\Models\Transaction;
use App\Repositories\{StockRepository, TransactionRepository};
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        protected StockRepository $stockRepo,
        protected ActivityLogService $logService
    ) {}

    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $user     = auth()->user();
            $branchId = $user->branch_id;

            // 1. Validasi stok semua item
            foreach ($data['items'] as $item) {
                $stock = $this->stockRepo->getStock($item['product_id'], $branchId);
                if ($stock->quantity < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk produk ID: {$item['product_id']}");
                }
            }

            // 2. Hitung total
            $total = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);

            // 3. Buat header transaksi
            $transaction = Transaction::create([
                'invoice_number'   => $this->generateInvoice($branchId),
                'branch_id'        => $branchId,
                'cashier_id'       => $user->id,
                'total_amount'     => $total,
                'discount'         => $data['discount'] ?? 0,
                'paid_amount'      => $data['paid_amount'],
                'change_amount'    => $data['paid_amount'] - $total,
                'status'           => 'completed',
                'transaction_date' => now(),
            ]);

            // 4. Simpan detail + update stok
            foreach ($data['items'] as $item) {
                $transaction->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal'   => $item['quantity'] * $item['unit_price'],
                ]);

                $this->stockRepo->decreaseStock(
                    $item['product_id'], $branchId,
                    $item['quantity'], $transaction->id, 'Transaction'
                );
            }

            // 5. Log aktivitas
            $this->logService->log('create_transaction', 'Transaction', $transaction->id);

            return $transaction;
        });
    }

    public function cancelTransaction(Transaction $transaction, string $reason): void
    {
        DB::transaction(function () use ($transaction, $reason) {
            // Kembalikan stok setiap item
            foreach ($transaction->items as $item) {
                $this->stockRepo->increaseStock(
                    $item->product_id, $transaction->branch_id,
                    $item->quantity, $transaction->id, 'Transaction',
                    'Pembatalan transaksi: ' . $reason
                );
            }

            $transaction->update([
                'status'       => 'cancelled',
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
                'cancel_reason'=> $reason,
            ]);

            $this->logService->log('cancel_transaction', 'Transaction', $transaction->id);
        });
    }

    private function generateInvoice(int $branchId): string
    {
        $branch   = \App\Models\Branch::find($branchId);
        $date     = now()->format('Ymd');
        $lastInv  = Transaction::where('branch_id', $branchId)
                               ->whereDate('transaction_date', today())
                               ->count();
        $seq = str_pad($lastInv + 1, 4, '0', STR_PAD_LEFT);
        return "INV-{$branch->code}-{$date}-{$seq}";
    }
}