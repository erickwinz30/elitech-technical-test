<?php

namespace App\Http\Controllers;

use App\Models\ProductionPlan;
use App\Models\ProductionOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionPlanController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$productionPlans = ProductionPlan::with(['product', 'creator', 'approver', 'productionOrder'])->get();
		$products = Product::all();
		$user = auth()->user();

		return Inertia::render('PPIC/Planning', [
			'plannings' => $productionPlans,
			'products' => $products,
			'user' => $user,
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
		DB::beginTransaction();
		try {
			$validatedData = $request->validate([
				'product_id' => 'required|exists:products,id',
				'planned_quantity' => 'required|integer|min:1',
				'deadline' => 'required|date|after:today',
				'notes' => 'nullable|string|max:255',
			]);

			ProductionPlan::create([
				'product_id' => $validatedData['product_id'],
				'created_by' => auth()->id(),
				'planned_quantity' => $validatedData['planned_quantity'],
				'deadline' => $validatedData['deadline'],
				'notes' => $validatedData['notes'] ?? null,
			]);

			DB::commit();

			return redirect()->route('ppic.planning')->with('success', 'Rencana produksi berhasil ditambahkan!');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Gagal menyimpan rencana produksi: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyimpan rencana produksi.');
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(ProductionPlan $productionPlan)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(ProductionPlan $productionPlan)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, ProductionPlan $productionPlan)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(ProductionPlan $productionPlan)
	{
		//
	}

	public function approve(ProductionPlan $planning)
	{
		DB::beginTransaction();
		try {
			// Update status planning menjadi approved
			$planning->update([
				'status' => 'approved',
				'approved_by' => auth()->id(),
				'approval_at' => now(),
			]);

			// Buat Production Order otomatis
			ProductionOrder::create([
				'production_plan_id' => $planning->id,
				'status' => 'pending',
				'target_completion_date' => $planning->deadline ?? now()->addDays(7),
				'actual_quantity' => null,
				'rejected_quantity' => 0,
			]);

			DB::commit();

			return redirect()->route('ppic.planning')->with('success', 'Rencana produksi berhasil disetujui dan Production Order telah dibuat!');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Gagal menyetujui rencana produksi: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menyetujui rencana produksi: ' . $e->getMessage());
		}
	}

	public function reject(ProductionPlan $planning)
	{
		DB::beginTransaction();
		try {
			$planning->update([
				'status' => 'rejected',
				'approved_by' => auth()->id(),
				'approval_at' => now(),
			]);

			DB::commit();

			return redirect()->route('ppic.planning')->with('success', 'Rencana produksi berhasil ditolak!');
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Gagal menolak rencana produksi: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Gagal menolak rencana produksi.');
		}
	}
}
