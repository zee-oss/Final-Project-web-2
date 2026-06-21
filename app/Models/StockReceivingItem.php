<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReceivingItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'stock_receiving_id',
        'product_id',
        'quantity',
        'buy_price',
        'subtotal'
    ];
}