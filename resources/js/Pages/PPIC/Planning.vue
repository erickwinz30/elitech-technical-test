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

interface Planning {
	id: number;
	product_id: number;
	created_by: string;
	approved_by: string | null;
	planned_quantity: number;
	status: "pending_approval" | "approved" | "rejected";
	deadline: string | null;
	approval_at: string | null;
	notes: string | null;
	product?: {
		id: number;
		product_code: string;
		product_name: string;
		description: string;
	};
	creator?: {
		id: string;
		name: string;
		email: string;
	};
	approver?: {
		id: string;
		name: string;
		email?: string;
	};
}

interface Product {
	id: number; // Ubah dari string ke number karena Laravel kirim sebagai int
	product_code: string;
	product_name: string;
	description: string;
}

interface User {
	id: string;
	role: string;
	department: string;
}

interface PlanningForm {
	product_id: string;
	planned_quantity: number;
	deadline: string;
	notes: string;
}

const props = defineProps<{
	plannings?: Planning[];
	products?: Product[];
	user?: User;
	flash?: {
		success?: string;
		error?: string;
	};
}>();

// Gunakan computed agar reaktif terhadap perubahan props
const plannings = computed(() => props.plannings || []);

// Check apakah user adalah Manager PPIC
const isManagerPPIC = computed(() => {
	return props.user?.role === "manager" && props.user?.department === "ppic";
});

console.log("Products from backend:", props.products);
console.log("Plannings from backend:", props.plannings);
console.log("Is Manager PPIC:", isManagerPPIC.value);

// DataTable columns configuration sebagai computed
const columns = computed(() => [
	{
		data: null,
		title: "No",
		render: (data: any, type: any, row: any, meta: any) => meta.row + 1,
	},
	{
		data: "product",
		title: "Nama Produk",
		render: (data: any) => {
			return data?.product_name || "-";
		},
	},
	{
		data: "creator",
		title: "Staff yang Mengajukan",
		render: (data: any) => {
			// data di sini SUDAH object creator, jadi langsung akses data.name
			return data?.name || "-";
		},
	},
	{
		data: "approver",
		title: "Disetujui/Ditolak oleh",
		render: (data: any) => {
			// data di sini SUDAH object approver, jadi langsung akses data.name
			return data?.name || "-";
		},
	},
	{ data: "planned_quantity", title: "Jumlah Rencana Produksi" },
	{
		data: "deadline",
		title: "Target Deadline",
		render: (data: any) => {
			return data || "-";
		},
	},
	{
		data: "approval_at",
		title: "Tanggal Persetujuan / Penolakan",
		render: (data: any) => {
			// data di sini SUDAH value dari approval_at (string atau null)
			return data || "-";
		},
	},
	{
		data: "status",
		title: "Status Planning",
		render: (data: any) => {
			const badges: Record<string, string> = {
				pending_approval:
					'<span class="badge bg-warning">Menunggu Persetujuan</span>',
				approved: '<span class="badge bg-success">Disetujui</span>',
				rejected: '<span class="badge bg-danger">Ditolak</span>',
			};
			return badges[data] || data;
		},
	},
	{
		data: null,
		title: "Aksi",
		orderable: false,
		render: (data: any, type: any, row: any) => {
			// Tampilkan tombol hanya jika status pending_approval DAN user adalah Manager PPIC
			if (row.status === "pending_approval" && isManagerPPIC.value) {
				return `
					<button type="button" class="btn btn-success btn-sm me-1" onclick="approvePlan(${row.id})" title="Setujui Rencana">
						<i class="bi bi-check-circle"></i>
					</button>
					<button type="button" class="btn btn-danger btn-sm" onclick="rejectPlan(${row.id})" title="Tolak Rencana">
						<i class="bi bi-x-circle"></i>
					</button>
				`;
			} else if (row.status === "pending_approval") {
				// Jika pending tapi bukan manager PPIC
				return '<span class="text-muted"><i class="bi bi-hourglass-split"></i> Menunggu Persetujuan Manager</span>';
			} else if (row.status === "approved") {
				return '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Sudah Disetujui</span>';
			} else if (row.status === "rejected") {
				return '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> Sudah Ditolak</span>';
			}
			return "-";
		},
	},
]);

const form = useForm<PlanningForm>({
	product_id: "",
	planned_quantity: 0,
	deadline: "",
	notes: "",
});

const submit = () => {
	form.post("/ppic/planning", {
		onSuccess: () => {
			form.reset();
			const modalElement = document.getElementById("addNewPlanningModal");
			const modal = (window as any).bootstrap.Modal.getInstance(modalElement);
			if (modal) {
				modal.hide();
			}
		},
	});
};

// Function untuk approve planning
const approvePlan = (planningId: number) => {
	const planning = plannings.value?.find((p) => p.id === planningId);

	if (!planning) {
		console.error("Planning not found. PlanningId:", planningId);
		return;
	}

	Swal.fire({
		title: "Konfirmasi Menyetujui Rencana Produksi",
		text: `Apakah Anda yakin ingin menyetujui rencana produksi ini?`,
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Setujui",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			router.post(`/ppic/planning/approve/${planning.id}`);
		}
	});
};

// Function untuk reject planning
const rejectPlan = (planningId: number) => {
	const planning = plannings.value?.find((p) => p.id === planningId);

	if (!planning) {
		console.error("Planning not found. PlanningId:", planningId);
		return;
	}

	Swal.fire({
		title: "Konfirmasi Menolak Rencana Produksi",
		text: `Apakah Anda yakin ingin menolak rencana produksi ini?`,
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Tolak",
		cancelButtonText: "Batal",
		confirmButtonColor: "#d33",
	}).then((result) => {
		if (result.isConfirmed) {
			router.post(`/ppic/planning/reject/${planning.id}`);
		}
	});
};

// Expose functions ke window
(window as any).approvePlan = approvePlan;
(window as any).rejectPlan = rejectPlan;
</script>
<template>
	<default-layout>
		<div class="pagetitle">
			<h1>Perencanaan Produksi</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item active">Perencanaan Produksi</li>
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
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h5 class="card-title">Daftar Rencana Produksi</h5>
								</div>
								<div>
									<button
										type="button"
										class="btn btn-success"
										data-bs-toggle="modal"
										data-bs-target="#addNewPlanningModal"
									>
										<i class="bi bi-plus-circle me-1"></i>
										Buat Rencana Baru
									</button>
								</div>
							</div>

							<div class="table-responsive p-3">
								<DataTable
									:data="plannings"
									:columns="columns"
									class="table table-striped table-hover"
								>
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Produk</th>
											<th>Staff yang Mengajukan</th>
											<th>Disetujui/Ditolak oleh</th>
											<th>Jumlah Rencana</th>
											<th>Target Deadline</th>
											<th>Tanggal Persetujuan / Penolakan</th>
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

			<!-- Create Planning Modal -->
			<div
				class="modal fade"
				id="addNewPlanningModal"
				tabindex="-1"
				aria-labelledby="newPlanningLabel"
				aria-hidden="true"
			>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="exampleModalLabel">
								Buat Rencana Produksi Baru
							</h1>
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="modal"
								aria-label="Close"
							></button>
						</div>
						<form @submit.prevent="submit">
							<div class="modal-body">
								<div class="mb-3">
									<label for="product_id" class="form-label"
										>Pilih Produk</label
									>
									<select
										class="form-select"
										id="product_id"
										name="product_id"
										v-model="form.product_id"
										required
									>
										<option value="" disabled>-- Pilih Produk --</option>
										<option
											v-for="product in products"
											:key="product.id"
											:value="product.id"
										>
											{{ product.product_name }}
										</option>
									</select>
								</div>
								<div class="mb-3">
									<label for="planned_quantity" class="form-label"
										>Jumlah Rencana Produksi</label
									>
									<input
										type="number"
										class="form-control"
										id="planned_quantity"
										name="planned_quantity"
										v-model.number="form.planned_quantity"
										min="1"
										required
									/>
								</div>
								<div class="mb-3">
									<label for="deadline" class="form-label"
										>Target Deadline</label
									>
									<input
										type="date"
										class="form-control"
										id="deadline"
										name="deadline"
										v-model="form.deadline"
										required
									/>
								</div>
								<div class="mb-3">
									<label for="notes" class="form-label"
										>Catatan (Opsional)</label
									>
									<textarea
										class="form-control"
										id="notes"
										name="notes"
										v-model="form.notes"
										rows="3"
									></textarea>
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
									class="btn btn-primary"
									:disabled="form.processing"
								>
									<span v-if="form.processing">Menyimpan...</span>
									<span v-else>Simpan Rencana</span>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</default-layout>
</template>
