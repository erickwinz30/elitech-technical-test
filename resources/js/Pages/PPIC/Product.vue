<script setup lang="ts">
import { defineProps, ref, Ref, onMounted } from "vue";
import { router, useForm } from "@inertiajs/vue3";

import DefaultLayout from "../Layout/DefaultLayout.vue";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.css";

import Swal from "sweetalert2";

// Register DataTables with Vue component
DataTable.use(DataTablesCore);

interface Product {
	id: number; // Ubah dari string ke number karena Laravel kirim sebagai int
	product_code: string;
	product_name: string;
	description: string;
}

interface ProductForm {
	product_code: string;
	product_name: string;
	description: string;
}

const props = defineProps<{
	products?: Product[];
}>();

const products: Ref<Product[] | undefined> = ref(props.products);

// DataTable columns configuration
const columns = [
	{
		data: null,
		title: "No",
		render: (data: any, type: any, row: any, meta: any) => meta.row + 1,
	},
	{ data: "product_code", title: "Kode Produk" },
	{ data: "product_name", title: "Nama Produk" },
	{ data: "description", title: "Deskripsi" },
	{
		data: null,
		title: "Aksi",
		orderable: false,
		render: (data: any, type: any, row: any) => {
			return `
				<button type="button" class="btn btn-danger btn-sm" onclick="deleteProduct(${row.id})">
					<i class="bi bi-trash"></i>
				</button>
			`;
		},
	},
];

const form = useForm<ProductForm>({
	product_code: "",
	product_name: "",
	description: "",
});

const submit = () => {
	form.post("/ppic/products", {
		onSuccess: () => {
			form.reset();
			// Close modal menggunakan Bootstrap
			const modalElement = document.getElementById("addNewProductModal");
			const modal = (window as any).bootstrap.Modal.getInstance(modalElement);
			if (modal) {
				modal.hide();
			}
		},
	});
};

// Function untuk delete product
const deleteProduct = (productId: number) => {
	const product = products.value?.find((product) => product.id === productId);

	if (!product) {
		console.error("Product not found. ProductId:", productId);
		return;
	}

	Swal.fire({
		title: "Konfirmasi Hapus",
		text: `Apakah Anda yakin ingin menghapus produk ${product.product_name}?`,
		icon: "warning",
		showCancelButton: true,
		confirmButtonText: "Hapus",
		cancelButtonText: "Batal",
	}).then((result) => {
		if (result.isConfirmed) {
			console.log("Menghapus produk dengan ID:", product.id);
			router.delete(`/ppic/products/${product.id}`);
		}
	});
};

// Expose function ke window agar bisa diakses dari onclick
(window as any).deleteProduct = deleteProduct;

console.log(products);
</script>

<template>
	<default-layout>
		<div class="pagetitle">
			<h1>Daftar Produk PPIC</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item active">Daftar Produk PPIC</li>
				</ol>
			</nav>
		</div>

		<section class="section">
			<div class="row justify-content-center">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<div class="d-flex justify-content-between align-items-center">
								<div>
									<h5 class="card-title">Daftar Produk</h5>
								</div>
								<div>
									<button
										type="button"
										class="btn btn-success"
										data-bs-toggle="modal"
										data-bs-target="#addNewProductModal"
									>
										Baru
									</button>
								</div>
							</div>

							<div class="table-responsive p-3">
								<DataTable
									:data="products || []"
									:columns="columns"
									class="table table-striped table-hover"
								>
									<thead>
										<tr>
											<th>No</th>
											<th>Kode Produk</th>
											<th>Nama Produk</th>
											<th>Deskripsi</th>
											<th>Aksi</th>
										</tr>
									</thead>
								</DataTable>
							</div>
							<!-- End Table with stripped rows -->
						</div>
					</div>
				</div>
			</div>

			<!-- Create Modal -->
			<div
				class="modal fade"
				id="addNewProductModal"
				tabindex="-1"
				aria-labelledby="newProductLabel"
				aria-hidden="true"
			>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="exampleModalLabel">
								Tambah Produk Baru
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
									<label for="code" class="form-label">Kode Produk Baru</label>
									<input
										type="text"
										class="form-control"
										id="code"
										name="code"
										v-model="form.product_code"
										required
										autofocus
									/>
								</div>
								<div class="mb-3">
									<label for="name" class="form-label">Nama Produk</label>
									<input
										type="text"
										class="form-control"
										id="name"
										name="name"
										v-model="form.product_name"
										required
									/>
								</div>
								<div class="mb-3">
									<label for="description" class="form-label"
										>Deskripsi Produk</label
									>
									<input
										type="text"
										class="form-control"
										id="description"
										name="description"
										v-model="form.description"
										required
									/>
								</div>
							</div>
							<div class="modal-footer">
								<button
									type="button"
									class="btn btn-secondary"
									data-bs-dismiss="modal"
								>
									Close
								</button>
								<button
									type="submit"
									class="btn btn-primary"
									:disabled="form.processing"
								>
									Save changes
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</default-layout>
</template>
