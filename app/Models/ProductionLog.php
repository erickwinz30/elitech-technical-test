<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionLog extends Model
{
	use HasFactory;

	const UPDATED_AT = null;

	protected $fillable = [
		'production_order_id',
		'changed_by',
		'status_from',
		'status_to',
		'notes',
	];

	/**
	 * Get the production order that owns the log.
	 */
	public function productionOrder()
	{
		return $this->belongsTo(ProductionOrder::class);
	}

	/**
	 * Get the user who made the change.
	 */
	public function changer()
	{
		return $this->belongsTo(User::class, 'changed_by');
	}
}
