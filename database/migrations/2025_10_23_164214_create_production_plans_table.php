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
		Schema::create('production_plans', function (Blueprint $table) {
			$table->id();
			$table->foreignId('product_id')->constrained('products');
			$table->foreignUuid('created_by')->constrained('users');
			$table->foreignUuid('approved_by')->nullable()->constrained('users');
			$table->integer('planned_quantity');
			$table->enum('status', ['pending_approval', 'approved', 'rejected'])->default('pending_approval');
			$table->date('deadline');
			$table->timestamp('approval_at')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('production_plans');
	}
};
