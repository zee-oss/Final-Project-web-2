<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku', 'barcode', 'name', 'category_id', 'unit',
        'buy_price', 'sell_price', 'min_stock', 'description', 'is_active'
    ];

    protected $casts = [
        'buy_price'  => 'decimal:2',
        'sell_price' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function category(): BelongsTo  { return $this->belongsTo(Category::class); }
    public function stocks(): HasMany       { return $this->hasMany(Stock::class); }
    public function movements(): HasMany    { return $this->hasMany(StockMovement::class); }

    // Stok di cabang tertentu
    public function stockAtBranch(int $branchId): ?Stock
    {
        return $this->stocks()->where('branch_id', $branchId)->first();
    }
}