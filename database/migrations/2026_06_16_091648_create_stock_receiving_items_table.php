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
         Schema::create('stock_receivings', function (Blueprint $table) {
        $table->id();
        $table->string('receiving_number', 30)->unique(); // RCV-CBG001-20260415-0001
        $table->foreignId('branch_id')->constrained('branches');
        $table->foreignId('supplier_id')->constrained('suppliers');
        $table->foreignId('received_by')->constrained('users');
        $table->date('receiving_date');
        $table->text('notes')->nullable();
        $table->timestamps();
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
