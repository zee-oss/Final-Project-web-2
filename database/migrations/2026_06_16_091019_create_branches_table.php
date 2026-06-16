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
       Schema::create('branches', function (Blueprint $table) {
        $table->id();
        $table->string('code', 10)->unique();         // CBG-001
        $table->string('name', 150);
        $table->text('address')->nullable();
        $table->string('city', 100);
        $table->string('phone', 20)->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
