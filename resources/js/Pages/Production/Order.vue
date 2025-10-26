<script setup lang="ts">
import DefaultLayout from "../Layout/DefaultLayout.vue";
import Alert from "../Components/Alert.vue";

import { defineProps, ref, Ref, computed } from "vue";
import { router, useForm } from "@inertiajs/vue3";

import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";

import Swal from "sweetalert2";

// Register DataTables with Vue component
DataTable.use(DataTablesCore);

interface Product {
	id: number;
	product_code: string;
	product_name: string;
	description: string | null;
	is_deleted: boolean;
}

interface User {
	id: string;
	name: string;
	email: string;
	username: string;
	role: "manager" | "staff";
	department: "ppic" | "production";
}

interface ProductionPlan {
	id: number;
	product_id: number;
	created_by: string;
	approved_by: string | null;
	planned_quantity: number;
	status: "pending_approval" | "approved" | "rejected";
	deadline: string;
	approval_at: string | null;
	notes: string | null;
	created_at: string;
	updated_at: string;
	// Nested relations
	product: Product;
	creator: User;
	approver: User | null;
}

interface Order {
	id: number;
	production_plan_id: number;
	status: "pending" | "in_progress" | "completed";
	actual_quantity: number | null;
	rejected_quantity: number;
	target_completion_date: string;
	actual_completion_date: string | null;
	created_at: string;
	updated_at: string;
	// Nested relation
	production_plan: ProductionPlan;
}

const props = defineProps<{
	orders?: Order[];
	user?: User;
	flash?: {
		success?: string;
		error?: string;
	};
}>();

const orders = computed(() => props.orders || []);

// Check apakah user adalah Manager atau Staff Production
const isManagerProduction = computed(() => {
	return (
		props.user?.role === "manager" && props.user?.department === "production"
	);
});

console.log("Orders from Backend:", props.orders);
console.log("User:", props.user);
console.log("Is Manager Production:", isManagerProduction.value);

// DataTable columns configuration
const columns = computed(() => [
	{
		data: null,
		title: "No",
		render: (data: any, type: any, row: any, meta: any) => meta.row + 1,
	},
	{
		data: "production_plan.product",
		title: "Nama Produk",
		render: (data: any) => {
			return data?.product_name || "-";
		},
	},
	{
		data: "production_plan",
		title: "Jumlah Rencana Produksi",
		render: (data: any) => {
			return data?.planned_quantity || "-";
		},
	},
	{
		data: "actual_quantity",
		title: "Jumlah Produksi",
		render: (data: any) => {
			return data !== null ? data : "-";
		},
	},
	{
		data: "rejected_quantity",
		title: "Jumlah Reject",
	},
	{
		data: "target_completion_date",
		title: "Target Selesai",
		render: (data: any) => {
			return data || "-";
		},
	},
	{
		data: "actual_completion_date",
		title: "Tanggal Selesai",
		render: (data: any) => {
			return data || "-";
		},
	},
	{
		data: "status",
		title: "Status Order",
		render: (data: any) => {
			const badges: Record<string, string> = {
				pending: '<span class="badge bg-warning">Pending</span>',
				in_progress: '<span class="badge bg-primary">Sedang Dikerjakan</span>',
				completed: '<span class="badge bg-success">Selesai</span>',
			};
			return badges[data] || data;
		},
	},
	{
		data: null,
		title: "Aksi",
		orderable: false,
		render: (data: any, type: any, row: any) => {
			if (row.status === "pending") {
				return `
					<button type="button" class="btn btn-primary btn-sm" onclick="startProduction(${row.id})" title="Mulai Produksi">
						<i class="bi bi-play-circle"></i> Mulai
					</button>
				`;
			} else if (row.status === "in_progress") {
				return `
					<button type="button" class="btn btn-success btn-sm" onclick="completeProduction(${row.id})" title="Selesaikan Produksi">
						<i class="bi bi-check-circle"></i> Selesai
					</button>
				`;
			} else if (row.status === "completed") {
				return '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Sudah Selesai</span>';
			}
			return "-";
		},
	},
]);

const startProduction = (orderId: number) => {
	const order = orders.value?.find((o) => o.id === orderId);

	if (!order) {
		console.error("Order not found. OrderId:", orderId);
		return;
	}

	Swal.fire({
		title: "Mulai Produksi",
		text: `Apakah Anda yakin ingin memulai produksi untuk produk ${order.production_plan.product.product_name}?`,
		icon: "question",
		showCancelButton: true,
		confirmButtonText: "Mulai",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			router.post(`/production/orders/start-production/${order.id}`);
		}
	});
};

const selectedOrder = ref<Order | null>(null);

interface CompleteProductionForm {
	actual_quantity: number;
	rejected_quantity: number;
}

const completeForm = useForm<CompleteProductionForm>({
	actual_quantity: 0,
	rejected_quantity: 0,
});

const openCompleteModal = (orderId: number) => {
	const order = orders.value?.find((o) => o.id === orderId);

	if (!order) {
		console.error("Order not found. OrderId:", orderId);
		return;
	}

	selectedOrder.value = order;

	// Set default value ke planned_quantity
	completeForm.actual_quantity = order.production_plan.planned_quantity;
	completeForm.rejected_quantity = 0;

	// Buka modal
	const modalElement = document.getElementById("completeProductionModal");
	const modal = new (window as any).bootstrap.Modal(modalElement);
	modal.show();
};

// Function untuk submit complete production dengan konfirmasi
const submitComplete = () => {
	if (!selectedOrder.value) return;

	Swal.fire({
		title: "Konfirmasi Selesai Produksi",
		text: `Apakah Anda yakin ingin menyelesaikan produksi untuk produk ${selectedOrder.value.production_plan.product.product_name}?`,
		icon: "question",
		showCancelButton: true,
		confirmButtonText: "Ya, Selesaikan",
		cancelButtonText: "Batal",
		confirmButtonColor: "#28a745",
	}).then((result) => {
		if (result.isConfirmed && selectedOrder.value) {
			completeForm.post(
				`/production/orders/complete-production/${selectedOrder.value.id}`,
				{
					onSuccess: () => {
						completeForm.reset();
						const modalElement = document.getElementById(
							"completeProductionModal"
						);
						const modal = (window as any).bootstrap.Modal.getInstance(
							modalElement
						);
						if (modal) {
							modal.hide();
						}
						selectedOrder.value = null;
					},
				}
			);
		}
	});
};

// Function untuk complete production (dipanggil dari DataTable)
const completeProduction = (orderId: number) => {
	openCompleteModal(orderId);
};

// Expose functions ke window
(window as any).startProduction = startProduction;
(window as any).completeProduction = completeProduction;
</script>
<template>
	<default-layout>
		<div class="pagetitle">
			<h1>Production Order</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item active">Production Order</li>
				</ol>
			</nav>
		</div>

		<Alert v-if="flash?.success" :message="flash.success" type="success" />
		<Alert v-if="flash?.error" :message="flash.error" type="error" />

		<section class="section">
			<div class="row justify-content-center">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Daftar Production Order</h5>

							<div class="table-responsive p-3">
								<DataTable
									:data="orders"
									:columns="columns"
									class="table table-striped table-hover"
								>
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Produk</th>
											<th>Jumlah Rencana Produksi</th>
											<th>Jumlah Produksi</th>
											<th>Jumlah Reject</th>
											<th>Target Selesai</th>
											<th>Tanggal Selesai</th>
											<th>Status</th>
											<th>Aksi</th>
										</tr>
									</thead>
								</DataTable>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Complete Production Modal -->
			<div
				class="modal fade"
				id="completeProductionModal"
				tabindex="-1"
				aria-labelledby="completeProductionLabel"
				aria-hidden="true"
			>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="completeProductionLabel">
								Selesaikan Produksi
							</h1>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
							></button>
						</div>
						<form @submit.prevent="submitComplete">
							<div class="modal-body">
								<div class="alert alert-info" v-if="selectedOrder">
									<strong>Produk:</strong>
									{{ selectedOrder.production_plan.product.product_name }}<br />
									<strong>Target Produksi:</strong>
									{{ selectedOrder.production_plan.planned_quantity }}
								</div>

								<div class="mb-3">
									<label for="actual_quantity" class="form-label">
										Jumlah Produksi Berhasil <span class="text-danger">*</span>
									</label>
									<input
										type="number"
										class="form-control"
										id="actual_quantity"
										name="actual_quantity"
										v-model.number="completeForm.actual_quantity"
										min="0"
										required
									/>
									<div class="form-text">
										Masukkan jumlah produk yang berhasil diproduksi
									</div>
								</div>

								<div class="mb-3">
									<label for="rejected_quantity" class="form-label">
										Jumlah Produksi Reject <span class="text-danger">*</span>
									</label>
									<input
										type="number"
										class="form-control"
										id="rejected_quantity"
										name="rejected_quantity"
										v-model.number="completeForm.rejected_quantity"
										min="0"
										required
									/>
									<div class="form-text">
										Masukkan jumlah produk yang reject/gagal
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button
									type="button"
									class="btn btn-secondary"
									data-bs-dismiss="modal"
								>
									Batal
								</button>
								<button
									type="submit"
									class="btn btn-success"
									:disabled="completeForm.processing"
								>
									<span v-if="completeForm.processing">Menyimpan...</span>
									<span v-else
										><i class="bi bi-check-circle me-1"></i>Selesaikan
										Produksi</span
									>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</default-layout>
</template>
