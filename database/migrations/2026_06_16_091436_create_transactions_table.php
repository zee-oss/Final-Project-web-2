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
       Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number', 30)->unique(); // INV-CBG001-20260415-0001
        $table->foreignId('branch_id')->constrained('branches');
        $table->foreignId('cashier_id')->constrained('users');
        $table->decimal('total_amount', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('paid_amount', 12, 2)->default(0);
        $table->decimal('change_amount', 12, 2)->default(0);
        $table->enum('status', ['completed', 'cancelled'])->default('completed');
        $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('cancelled_at')->nullable();
        $table->text('cancel_reason')->nullable();
        $table->timestamp('transaction_date')->useCurrent();
        $table->timestamps();

        // Index untuk query laporan
        $table->index(['branch_id', 'transaction_date']);
        $table->index(['cashier_id', 'transaction_date']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
