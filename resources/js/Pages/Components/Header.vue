<script setup>
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth?.user || page.props.user);

const logout = () => {
	router.post("/logout");
};

const getRoleLabel = (role) => {
	const roleLabels = {
		manager: "Manager",
		staff: "Staff",
	};
	return roleLabels[role] || role;
};
</script>

<template>
	<!-- ======= Header ======= -->
	<header
		id="header"
		class="header fixed-top d-flex align-items-center justify-content-between"
	>
		<div class="d-flex align-items-center justify-content-between">
			<a href="/stock" class="logo d-flex align-items-center">
				<img src="/images/elitech_logo.jpg" alt="Elitech Logo" />
				<span class="d-none d-lg-block fs-5">Elitech</span>
			</a>
			<i class="bi bi-list toggle-sidebar-btn"></i>
		</div>
		<!-- End Logo -->

		<nav class="header-nav ms-auto">
			<ul class="d-flex align-items-center">
				<li class="nav-item dropdown pe-3">
					<a
						class="nav-link nav-profile d-flex align-items-center pe-0"
						href="#"
						data-bs-toggle="dropdown"
					>
						<span class="d-none d-md-block dropdown-toggle ps-2">
							{{ user?.name || "Guest" }}
						</span>
					</a>
					<!-- End Profile Image Icon -->

					<ul
						class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
					>
						<li class="dropdown-header">
							<h6>{{ user?.name || "Guest" }}</h6>
							<span>{{ getRoleLabel(user?.role) }}</span>
						</li>
						<li>
							<hr class="dropdown-divider" />
						</li>

						<li>
							<a
								class="dropdown-item d-flex align-items-center"
								href="#"
								@click.prevent="logout"
							>
								<i class="bi bi-box-arrow-right"></i>
								<span>Log Out</span>
							</a>
						</li>
					</ul>
					<!-- End Profile Dropdown Items -->
				</li>
				<!-- End Profile Nav -->
			</ul>
		</nav>
		<!-- End Icons Navigation -->
	</header>
	<!-- End Header -->
</template>
