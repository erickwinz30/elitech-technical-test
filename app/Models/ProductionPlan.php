<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionPlan extends Model
{
	use HasFactory;

	protected $fillable = [
		'product_id',
		'created_by',
		'approved_by',
		'planned_quantity',
		'status',
		'deadline',
		'approval_at',
		'notes',
	];

	/**
	 * Get the product that owns the production plan.
	 */
	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	/**
	 * Get the user who created the plan.
	 */
	public function creator()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	/**
	 * Get the user who approved the plan.
	 */
	public function approver()
	{
		return $this->belongsTo(User::class, 'approved_by');
	}

	/**
	 * Get the production order for this plan.
	 */
	public function productionOrder()
	{
		return $this->hasOne(ProductionOrder::class);
	}
}
