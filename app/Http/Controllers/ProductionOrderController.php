<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductionOrderController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$productionOrders = ProductionOrder::with(['productionPlan.product'])->get();

		return Inertia::render('Production/Order', [
			'orders' => $productionOrders,
			'flash' => [
				'success' => session('success'),
				'error' => session('error'),
			]
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ProductionOrder $productionOrder)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ProductionOrder $productionOrder)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, ProductionOrder $productionOrder)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ProductionOrder $productionOrder)
	{
		//
	}

	public function startProduction(ProductionOrder $productionOrder)
	{
		DB::beginTransaction();
		try {
			$productionOrder->update(['status' => 'in_progress']);

			ProductionLog::create([
				'production_order_id' => $productionOrder->id,
				'changed_by' => auth()->id(),
				'status_from' => 'pending',
				'status_to' => 'in_progress',
			]);

			DB::commit();
			return redirect()->back()->with('success', 'Produksi dimulai.');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info('Gagal memulai produksi: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal memulai produksi: ' . $e->getMessage());
		}
	}

	public function completeProduction(Request $request, ProductionOrder $productionOrder)
	{
		DB::beginTransaction();
		try {
			$validatedData = $request->validate([
				'actual_quantity' => 'required|integer|min:0',
				'rejected_quantity' => 'required|integer|min:0',
			]);

			$productionOrder->update([
				'status' => 'completed',
				'actual_quantity' => $validatedData['actual_quantity'],
				'rejected_quantity' => $validatedData['rejected_quantity'],
				'actual_completion_date' => now(),
			]);

			ProductionLog::create([
				'production_order_id' => $productionOrder->id,
				'changed_by' => auth()->id(),
				'status_from' => 'in_progress',
				'status_to' => 'completed',
				'notes' => 'Produksi selesai. Total produksi: ' . $validatedData['actual_quantity'] . ' unit, Reject: ' . $validatedData['rejected_quantity'] . ' unit',
			]);

			DB::commit();

			return redirect()->back()->with('success', 'Produksi selesai.');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info('Gagal menyelesaikan produksi: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyelesaikan produksi: ' . $e->getMessage());
		}
	}

	public function logs()
	{
		$logs = ProductionLog::with(['productionOrder.productionPlan.product', 'changedBy'])->orderBy('created_at', 'desc')->get();

		return Inertia::render('Production/Logs', [
			'logs' => $logs,
			'flash' => [
				'success' => session('success'),
				'error' => session('error'),
			]
		]);
	}
}
