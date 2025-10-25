<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const currentUrl = computed(() => page.url);

// Function untuk check apakah route sedang aktif
const isActiveRoute = (route: string) => {
	return currentUrl.value === route || currentUrl.value.startsWith(route);
};

// Function untuk mendapatkan class nav-link
const getNavLinkClass = (route: string) => {
	return isActiveRoute(route) ? "nav-link" : "nav-link collapsed";
};
</script>

<template>
	<aside id="sidebar" class="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">
			<li class="nav-heading">PPIC</li>

			<li class="nav-item">
				<a :class="getNavLinkClass('/ppic/planning')" href="/ppic/planning">
					<i class="bi bi-graph-up"></i>
					<span>Perencanaan</span>
				</a>
			</li>

			<li class="nav-item">
				<a :class="getNavLinkClass('/ppic/products')" href="/ppic/products">
					<i class="bi bi-list-ul"></i>
					<span>Daftar Produk</span>
				</a>
			</li>

			<!-- <li class="nav-heading">Admin</li>

			<li class="nav-item">
				<a class="nav-link" href="/">
					<i class="bi bi-speedometer"></i>
					<span>Dashboard</span>
				</a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/users">
					<i class="bi bi-person-gear"></i>
					<span>User</span>
				</a>
			</li> -->
		</ul>
	</aside>
</template>
