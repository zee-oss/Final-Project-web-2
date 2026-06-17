<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stock';
    protected $fillable = ['product_id', 'branch_id', 'quantity'];

    protected $casts = ['quantity' => 'decimal:3'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function branch(): BelongsTo  { return $this->belongsTo(Branch::class); }

    // Cek apakah stok di bawah minimum
    public function isBelowMinimum(): bool
    {
        return $this->quantity < $this->product->min_stock;
    }
}