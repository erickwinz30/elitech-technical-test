<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$products = Product::where('is_deleted', false)->get();
		return Inertia::render('PPIC/Product', ['products' => $products]);
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
		Log::info('Menyimpan produk baru dengan data: ' . json_encode($request->all()));
		DB::beginTransaction();
		try {
			$validatedData = $request->validate([
				'product_code' => 'required|unique:products,product_code|max:50',
				'product_name' => 'required|max:100',
				'description' => 'nullable|max:255',
			]);
			Product::create($validatedData);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			Log::error('Gagal menyimpan produk: ' . $e->getMessage());
			// Handle the error
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Product $product)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Product $product)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Product $product)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Product $product)
	{
		DB::beginTransaction();
		Log::info('Menghapus produk dengan ID: ' . $product->id);

		try {
			$product->is_deleted = true;
			$product->save();
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
		}
	}
}
