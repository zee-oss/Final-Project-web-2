<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockReceiving extends Model
{
    protected $fillable = [
        'receiving_number',
        'branch_id',
        'supplier_id',
        'received_by',
        'receiving_date',
        'notes'
    ];
}