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
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_plan_id')->unique()->constrained('production_plans');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->integer('actual_quantity')->nullable();
            $table->integer('rejected_quantity')->default(0);
            $table->date('target_completion_date');
            $table->timestamp('actual_completion_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_orders');
    }
};
