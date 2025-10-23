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
}
