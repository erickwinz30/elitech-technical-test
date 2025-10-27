<script setup lang="ts">
import DefaultLayout from "../Layout/DefaultLayout.vue";
import Alert from "../Components/Alert.vue";

import { defineProps, ref, computed } from "vue";

import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";

import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.html5";
import "datatables.net-buttons/js/buttons.print";
import "datatables.net-buttons-bs5/css/buttons.bootstrap5.css";

import * as pdfMake from "pdfmake/build/pdfmake";
import * as pdfFonts from "pdfmake/build/vfs_fonts";
import JSZip from "jszip";

(pdfMake as any).vfs = pdfFonts;
(window as any).JSZip = JSZip;

DataTable.use(DataTablesCore);

interface User {
	id: string;
	name: string;
	email: string;
	username: string;
	role: "manager" | "staff";
	department: "ppic" | "production";
}

interface Product {
	id: number;
	product_code: string;
	product_name: string;
	description: string | null;
}

interface ProductionPlan {
	id: number;
	product_id: number;
	planned_quantity: number;
	deadline: string;
	product: Product;
}

interface ProductionOrder {
	id: number;
	production_plan_id: number;
	status: "pending" | "in_progress" | "completed";
	production_plan: ProductionPlan;
}

interface Log {
	id: number;
	production_order_id: number;
	changed_by: string;
	status_from: "pending" | "in_progress" | "completed" | null;
	status_to: "pending" | "in_progress" | "completed";
	notes: string | null;
	created_at: string;
	production_order: ProductionOrder;
	changer: User;
}

const props = defineProps<{
	logs?: Log[];
	user?: User;
	flash?: {
		success?: string;
		error?: string;
	};
}>();

const logs = computed(() => props.logs || []);

const filterStartDate = ref<string>("");
const filterEndDate = ref<string>("");

const filteredLogs = computed(() => {
	let result = logs.value;

	if (filterStartDate.value) {
		const startDate = new Date(filterStartDate.value);
		startDate.setHours(0, 0, 0, 0);
		result = result.filter((log) => {
			const createdDate = new Date(log.created_at);
			return createdDate >= startDate;
		});
	}

	if (filterEndDate.value) {
		const endDate = new Date(filterEndDate.value);
		endDate.setHours(23, 59, 59, 999);
		result = result.filter((log) => {
			const createdDate = new Date(log.created_at);
			return createdDate <= endDate;
		});
	}

	return result;
});

const resetFilter = () => {
	filterStartDate.value = "";
	filterEndDate.value = "";
};

const formatDateTime = (dateString: string | null): string => {
	if (!dateString) return "-";

	const date = new Date(dateString);
	const year = date.getFullYear();
	const month = String(date.getMonth() + 1).padStart(2, "0");
	const day = String(date.getDate()).padStart(2, "0");
	const hours = String(date.getHours()).padStart(2, "0");
	const minutes = String(date.getMinutes()).padStart(2, "0");

	return `${day}/${month}/${year}, ${hours}:${minutes}`;
};

const columns = computed(() => [
	{
		data: null,
		title: "No",
		render: (data: any, type: any, row: any, meta: any) => meta.row + 1,
	},
	{
		data: "production_order.production_plan.product",
		title: "Produk",
		render: (data: any) => {
			return data?.product_name || "-";
		},
	},
	{
		data: "production_order",
		title: "Order ID",
		render: (data: any) => {
			return data?.id || "-";
		},
	},
	{
		data: "status_from",
		title: "Status Awal",
		render: (data: any) => {
			if (!data) return '<span class="badge bg-secondary">-</span>';
			const badges: Record<string, string> = {
				pending: '<span class="badge bg-warning">Pending</span>',
				in_progress: '<span class="badge bg-primary">In Progress</span>',
				completed: '<span class="badge bg-success">Completed</span>',
			};
			return badges[data] || data;
		},
	},
	{
		data: "status_to",
		title: "Status Tujuan",
		render: (data: any) => {
			const badges: Record<string, string> = {
				pending: '<span class="badge bg-warning">Pending</span>',
				in_progress: '<span class="badge bg-primary">In Progress</span>',
				completed: '<span class="badge bg-success">Completed</span>',
			};
			return badges[data] || data;
		},
	},
	{
		data: "changer",
		title: "Diubah Oleh",
		render: (data: any) => {
			return data?.name || "-";
		},
	},
	{
		data: "created_at",
		title: "Waktu Perubahan",
		render: (data: any) => {
			return formatDateTime(data);
		},
	},
	{
		data: "notes",
		title: "Catatan",
		render: (data: any) => {
			return data || "-";
		},
	},
]);

const options = {
	buttons: [
		{
			extend: "copy",
			text: '<i class="bi bi-files me-1"></i> Copy',
			className: "btn btn-secondary btn-sm",
		},
		{
			extend: "excel",
			text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
			className: "btn btn-success btn-sm",
			title: "Riwayat Perubahan Status Produksi",
		},
		{
			extend: "pdf",
			text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
			className: "btn btn-danger btn-sm",
			title: "Riwayat Perubahan Status Produksi",
			orientation: "landscape",
			pageSize: "A4",
		},
		{
			extend: "print",
			text: '<i class="bi bi-printer me-1"></i> Print',
			className: "btn btn-info btn-sm",
			title: "Riwayat Perubahan Status Produksi",
		},
	],
};

console.log("Logs from backend:", props.logs);
</script>

<template>
	<default-layout>
		<div class="pagetitle">
			<h1>Production Logs</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item active">Production Logs</li>
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
							<h5 class="card-title">Riwayat Perubahan Status Produksi</h5>

							<!-- Filter Section -->
							<div class="mb-3 p-3 bg-light rounded">
								<div class="row align-items-end">
									<div class="col-md-4">
										<label for="filter_start_date" class="form-label">
											<i class="bi bi-calendar-event me-1"></i>
											Tanggal Mulai
										</label>
										<input
											type="date"
											class="form-control"
											id="filter_start_date"
											v-model="filterStartDate"
										/>
									</div>
									<div class="col-md-4">
										<label for="filter_end_date" class="form-label">
											<i class="bi bi-calendar-event me-1"></i>
											Tanggal Akhir
										</label>
										<input
											type="date"
											class="form-control"
											id="filter_end_date"
											v-model="filterEndDate"
										/>
									</div>
									<div class="col-md-4">
										<button
											type="button"
											class="btn btn-secondary"
											@click="resetFilter"
										>
											<i class="bi bi-arrow-clockwise me-1"></i>
											Reset Filter
										</button>
									</div>
								</div>
								<div
									class="mt-2 text-muted"
									v-if="filterStartDate || filterEndDate"
								>
									<small>
										<i class="bi bi-info-circle me-1"></i>
										Menampilkan {{ filteredLogs.length }} dari
										{{ logs.length }} data
									</small>
								</div>
							</div>

							<div class="table-responsive p-3">
								<DataTable
									:data="filteredLogs"
									:columns="columns"
									:options="options"
									class="table table-striped table-hover"
								>
									<thead>
										<tr>
											<th>No</th>
											<th>Produk</th>
											<th>Order ID</th>
											<th>Status Awal</th>
											<th>Status Tujuan</th>
											<th>Diubah Oleh</th>
											<th>Waktu Perubahan</th>
											<th>Catatan</th>
										</tr>
									</thead>
								</DataTable>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</default-layout>
</template>
