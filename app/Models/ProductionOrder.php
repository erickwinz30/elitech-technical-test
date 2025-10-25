<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
	use HasFactory;

	protected $fillable = [
		'production_plan_id',
		'status',
		'actual_quantity',
		'rejected_quantity',
		'target_completion_date',
		'actual_completion_date',
	];

	/**
	 * Get the production plan that owns the order.
	 */
	public function productionPlan()
	{
		return $this->belongsTo(ProductionPlan::class);
	}
}
