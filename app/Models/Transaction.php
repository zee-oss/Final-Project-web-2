<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice_number', 'branch_id', 'cashier_id', 'total_amount',
        'discount', 'paid_amount', 'change_amount', 'status',
        'cancelled_by', 'cancelled_at', 'cancel_reason', 'transaction_date'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'cancelled_at'     => 'datetime',
        'total_amount'     => 'decimal:2',
        'paid_amount'      => 'decimal:2',
        'change_amount'    => 'decimal:2',
    ];

    public function branch(): BelongsTo   { return $this->belongsTo(Branch::class); }
    public function cashier(): BelongsTo  { return $this->belongsTo(User::class, 'cashier_id'); }
    public function cancelledBy(): BelongsTo { return $this->belongsTo(User::class, 'cancelled_by'); }
    public function items(): HasMany       { return $this->hasMany(TransactionItem::class); }
}