<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('transaction_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
        $table->foreignId('product_id')->constrained('products');
        $table->decimal('quantity', 12, 3)->default(0);
        $table->decimal('unit_price', 12, 2)->default(0);  // harga saat transaksi
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->timestamp('created_at')->useCurrent();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
