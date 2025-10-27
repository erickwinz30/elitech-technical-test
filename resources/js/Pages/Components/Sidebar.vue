<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const currentUrl = computed(() => page.url);
const user = computed(() => page.props.auth?.user || page.props.user);

const getAccessLabel = (role) => {
	const roleLabels = {
		manager: "Manager",
		staff: "Staff",
	};
	return roleLabels[role] || role;
};

const isActiveRoute = (route: string) => {
	return currentUrl.value === route || currentUrl.value.startsWith(route);
};

const getNavLinkClass = (route: string) => {
	return isActiveRoute(route) ? "nav-link" : "nav-link collapsed";
};

// Check if user can access PPIC menu
const canAccessPPIC = computed(() => {
	if (!user.value) return false;
	// Manager can access both
	if (user.value.role === "manager") return true;
	// Staff can only access their department
	return user.value.department === "ppic";
});

// Check if user can access Production menu
const canAccessProduction = computed(() => {
	if (!user.value) return false;
	// Manager can access both
	if (user.value.role === "manager") return true;
	// Staff can only access their department
	return user.value.department === "production";
});
</script>

<template>
	<aside id="sidebar" class="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">
			<!-- PPIC Section - Only show if user can access -->
			<template v-if="canAccessPPIC">
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
			</template>

			<template v-if="canAccessProduction">
				<li class="nav-heading">Produksi</li>

				<li class="nav-item">
					<a
						:class="getNavLinkClass('/production/orders')"
						href="/production/orders"
					>
						<i class="bi bi-speedometer"></i>
						<span>Order Produksi</span>
					</a>
				</li>

				<li class="nav-item">
					<a
						:class="getNavLinkClass('/production/logs')"
						href="/production/logs"
					>
						<i class="bi bi-clock-history"></i>
						<span>Riwayat Order</span>
					</a>
				</li>
			</template>
		</ul>
	</aside>
</template>
