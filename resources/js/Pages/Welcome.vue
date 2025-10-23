<script setup lang="ts">
import { defineProps } from "vue";
import DefaultLayout from "./Layout/DefaultLayout.vue";
// Anda bisa menambahkan logika <script setup> di sini
</script>

<template>
	<default-layout>
		<div>
			<h1>Halo, Inertia.js!</h1>
			<p>
				Selamat datang di halaman Vue pertama Anda yang di-render dari Laravel.
			</p>

			<button class="btn btn-primary">Ini Tombol Bootstrap</button>
		</div>
	</default-layout>
</template>
