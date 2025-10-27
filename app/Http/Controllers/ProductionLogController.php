<?php

namespace App\Http\Controllers;

use App\Models\ProductionLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductionLogController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$logs = ProductionLog::with([
			'productionOrder.productionPlan.product',
			'changer'
		])->orderBy('created_at', 'desc')->get();

		$user = auth()->user();

		return Inertia::render('Production/Logs', [
			'logs' => $logs,
			'user' => $user,
			'flash' => [
				'success' => session('success'),
				'error' => session('error'),
			]
		]);
	}
}
