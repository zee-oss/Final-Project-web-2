<?php
namespace App\Repositories;

use App\Models\{Stock, StockMovement};
use Illuminate\Support\Facades\DB;

class StockRepository
{
    public function getStock(int $productId, int $branchId): Stock
    {
        return Stock::firstOrCreate(
            ['product_id' => $productId, 'branch_id' => $branchId],
            ['quantity' => 0]
        );
    }

    public function decreaseStock(
        int $productId, int $branchId, float $qty,
        int $referenceId, string $referenceType
    ): void {
        $stock = $this->getStock($productId, $branchId);
        $before = $stock->quantity;
        $stock->decrement('quantity', $qty);

        StockMovement::create([
            'product_id'     => $productId,
            'branch_id'      => $branchId,
            'user_id'        => auth()->id(),
            'type'           => 'out',
            'quantity'       => $qty,
            'stock_before'   => $before,
            'stock_after'    => $before - $qty,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
        ]);
    }

    public function increaseStock(
        int $productId, int $branchId, float $qty,
        int $referenceId, string $referenceType, string $notes = null
    ): void {
        $stock = $this->getStock($productId, $branchId);
        $before = $stock->quantity;
        $stock->increment('quantity', $qty);

        StockMovement::create([
            'product_id'     => $productId,
            'branch_id'      => $branchId,
            'user_id'        => auth()->id(),
            'type'           => 'in',
            'quantity'       => $qty,
            'stock_before'   => $before,
            'stock_after'    => $before + $qty,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
            'notes'          => $notes,
        ]);
    }

    public function adjustStock(
        int $productId, int $branchId, float $newQty, string $notes
    ): void {
        $stock  = $this->getStock($productId, $branchId);
        $before = $stock->quantity;
        $diff   = $newQty - $before;
        $stock->update(['quantity' => $newQty]);

        StockMovement::create([
            'product_id'   => $productId,
            'branch_id'    => $branchId,
            'user_id'      => auth()->id(),
            'type'         => 'adjustment',
            'quantity'     => abs($diff),
            'stock_before' => $before,
            'stock_after'  => $newQty,
            'notes'        => $notes,
        ]);
    }
}