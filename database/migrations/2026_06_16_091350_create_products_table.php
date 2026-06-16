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
         Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('sku', 50)->unique();
        $table->string('barcode', 50)->nullable()->unique();
        $table->string('name', 200);
        $table->foreignId('category_id')->constrained('categories');
        $table->string('unit', 30);                   
        $table->decimal('buy_price', 12, 2)->default(0);
        $table->decimal('sell_price', 12, 2)->default(0);
        $table->integer('min_stock')->default(5);    
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
