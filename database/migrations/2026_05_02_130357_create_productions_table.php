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
        Schema::create('productions', function (Blueprint $table) {
            $table->id(); //ProductionID
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('prod_status')->default('Pending'); 
            $table->text('prod_note')->nullable();
            $table->date('prod_start_date')->nullable();
            $table->date('prod_finished_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};
