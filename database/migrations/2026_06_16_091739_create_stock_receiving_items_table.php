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
        Schema::create('stock_receiving_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stock_receiving_id')->constrained('stock_receivings')->cascadeOnDelete();
        $table->foreignId('product_id')->constrained('products');
        $table->decimal('quantity', 12, 3);
        $table->decimal('buy_price', 12, 2);
        $table->decimal('subtotal', 12, 2);
        $table->timestamp('created_at')->useCurrent();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_receiving_items');
    }
};
