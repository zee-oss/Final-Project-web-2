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
        Schema::create('stock_movements', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products');
        $table->foreignId('branch_id')->constrained('branches');
        $table->foreignId('user_id')->constrained('users');
        $table->enum('type', ['in', 'out', 'adjustment']);
        $table->decimal('quantity', 12, 3);
        $table->decimal('stock_before', 12, 3);
        $table->decimal('stock_after', 12, 3);
        $table->string('reference_type', 50)->nullable(); // Transaction, StockReceiving
        $table->unsignedBigInteger('reference_id')->nullable();
        $table->text('notes')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->index(['branch_id', 'product_id', 'created_at']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
