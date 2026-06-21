<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Models\StockReceiving;

class StockReceivingController extends Controller
{
    public function index()
    {
        $receivings = StockReceiving::latest()->paginate(20);

        return view('stock.receivings.index', compact('receivings'));
    }
}