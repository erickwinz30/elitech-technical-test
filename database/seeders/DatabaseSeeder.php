<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductionPlan;
use App\Models\ProductionOrder;
use App\Models\ProductionLog;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		User::truncate();
		Product::truncate();
		ProductionPlan::truncate();
		ProductionOrder::truncate();
		ProductionLog::truncate();

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

		$managerPPIC = User::create([
			'name' => 'Manager 1',
			'username' => 'manager1',
			'password' => bcrypt('manager1'),
			'role' => 'manager',
			'department' => 'ppic',
		]);

		$managerProduction = User::create([
			'name' => 'Manager 2',
			'username' => 'manager2',
			'password' => bcrypt('manager2'),
			'role' => 'manager',
			'department' => 'production',
		]);

		$staffPPIC = User::create([
			'name' => 'Staff PPIC 1',
			'username' => 'staff1',
			'password' => bcrypt('staff1'),
			'role' => 'staff',
			'department' => 'ppic',
		]);

		$staffProduction = User::create([
			'name' => 'Staff Production 1',
			'username' => 'staff2',
			'password' => bcrypt('staff2'),
			'role' => 'staff',
			'department' => 'production',
		]);

		$product1 = Product::create([
			'product_code' => 'PM001',
			'product_name' => 'Patient Monitor',
			'description' => 'Alat monitor pasien pada rumah sakit',
		]);

		$product2 = Product::create([
			'product_code' => 'IP001',
			'product_name' => 'Infusion Pump',
			'description' => 'Alat pompa infus untuk rumah sakit',
		]);

		$product3 = Product::create([
			'product_code' => 'ECG001',
			'product_name' => 'ECG Machine',
			'description' => 'Alat untuk merekam aktivitas listrik jantung',
		]);

		$product4 = Product::create([
			'product_code' => 'OXY001',
			'product_name' => 'Oxygen Concentrator',
			'description' => 'Alat konsentrator oksigen untuk terapi pernapasan',
		]);

		$product5 = Product::create([
			'product_code' => 'USG001',
			'product_name' => 'Ultrasound Machine',
			'description' => 'Alat USG untuk diagnostik medis',
		]);

		$plan1 = ProductionPlan::create([
			'product_id' => $product1->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => null,
			'planned_quantity' => 50,
			'status' => 'pending_approval',
			'deadline' => now()->addDays(30),
			'approval_at' => null,
			'notes' => 'Permintaan urgent dari RS Siloam',
			'created_at' => now()->subDays(2),
		]);

		$plan2 = ProductionPlan::create([
			'product_id' => $product2->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => $managerPPIC->id,
			'planned_quantity' => 100,
			'status' => 'approved',
			'deadline' => now()->addDays(45),
			'approval_at' => now()->subDays(3),
			'notes' => 'Order rutin bulanan',
			'created_at' => now()->subDays(5),
		]);

		$plan3 = ProductionPlan::create([
			'product_id' => $product3->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => $managerPPIC->id,
			'planned_quantity' => 75,
			'status' => 'approved',
			'deadline' => now()->addDays(20),
			'approval_at' => now()->subDays(10),
			'notes' => 'Pre-order dari distributor',
			'created_at' => now()->subDays(12),
		]);

		$plan4 = ProductionPlan::create([
			'product_id' => $product4->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => $managerPPIC->id,
			'planned_quantity' => 60,
			'status' => 'approved',
			'deadline' => now()->addDays(15),
			'approval_at' => now()->subDays(15),
			'notes' => 'Stok untuk warehouse',
			'created_at' => now()->subDays(17),
		]);

		$plan5 = ProductionPlan::create([
			'product_id' => $product5->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => $managerPPIC->id,
			'planned_quantity' => 200,
			'status' => 'rejected',
			'deadline' => now()->addDays(60),
			'approval_at' => now()->subDays(1),
			'notes' => 'Kapasitas produksi terlalu besar',
			'created_at' => now()->subDays(3),
		]);

		$plan6 = ProductionPlan::create([
			'product_id' => $product1->id,
			'created_by' => $staffPPIC->id,
			'approved_by' => $managerPPIC->id,
			'planned_quantity' => 80,
			'status' => 'approved',
			'deadline' => now()->addDays(25),
			'approval_at' => now()->subDays(8),
			'notes' => 'Restock patient monitor',
			'created_at' => now()->subDays(10),
		]);

		$order1 = ProductionOrder::create([
			'production_plan_id' => $plan2->id,
			'status' => 'pending',
			'actual_quantity' => null,
			'rejected_quantity' => 0,
			'target_completion_date' => $plan2->deadline,
			'actual_completion_date' => null,
			'created_at' => now()->subDays(3),
		]);

		$order2 = ProductionOrder::create([
			'production_plan_id' => $plan3->id,
			'status' => 'in_progress',
			'actual_quantity' => null,
			'rejected_quantity' => 0,
			'target_completion_date' => $plan3->deadline,
			'actual_completion_date' => null,
			'created_at' => now()->subDays(10),
		]);

		$order3 = ProductionOrder::create([
			'production_plan_id' => $plan4->id,
			'status' => 'completed',
			'actual_quantity' => 58,
			'rejected_quantity' => 2,
			'target_completion_date' => $plan4->deadline,
			'actual_completion_date' => now()->subDays(2),
			'created_at' => now()->subDays(15),
		]);

		$order4 = ProductionOrder::create([
			'production_plan_id' => $plan6->id,
			'status' => 'in_progress',
			'actual_quantity' => null,
			'rejected_quantity' => 0,
			'target_completion_date' => $plan6->deadline,
			'actual_completion_date' => null,
			'created_at' => now()->subDays(8),
		]);

		ProductionLog::create([
			'production_order_id' => $order2->id,
			'changed_by' => $staffProduction->id,
			'status_from' => 'pending',
			'status_to' => 'in_progress',
			'notes' => 'Memulai proses produksi ECG Machine',
			'created_at' => now()->subDays(9),
		]);

		ProductionLog::create([
			'production_order_id' => $order3->id,
			'changed_by' => $staffProduction->id,
			'status_from' => 'pending',
			'status_to' => 'in_progress',
			'notes' => 'Memulai produksi Oxygen Concentrator',
			'created_at' => now()->subDays(14),
		]);

		ProductionLog::create([
			'production_order_id' => $order3->id,
			'changed_by' => $staffProduction->id,
			'status_from' => 'in_progress',
			'status_to' => 'completed',
			'notes' => 'Produksi selesai. Total produksi: 58 unit, Reject: 2 unit',
			'created_at' => now()->subDays(2),
		]);

		ProductionLog::create([
			'production_order_id' => $order4->id,
			'changed_by' => $staffProduction->id,
			'status_from' => 'pending',
			'status_to' => 'in_progress',
			'notes' => 'Memulai produksi Patient Monitor batch 2',
			'created_at' => now()->subDays(7),
		]);
	}
}
