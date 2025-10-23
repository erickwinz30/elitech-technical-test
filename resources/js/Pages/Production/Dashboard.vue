<script setup>
import DefaultLayout from "../Layout/DefaultLayout.vue";
</script>

<template>
	<default-layout>
		<div class="pagetitle">
			<h1>Dashboard Production</h1>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Home</a></li>
					<li class="breadcrumb-item active">Production</li>
				</ol>
			</nav>
		</div>
		<!-- End Page Title -->

		<section class="section dashboard">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Halaman Production</h5>
							<p>Selamat datang di halaman Production.</p>
							<p>
								Halaman ini hanya dapat diakses oleh user dengan department
								Production.
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>
	</default-layout>
</template>
