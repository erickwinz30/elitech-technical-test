<script setup>
// Function untuk mengecek apakah route aktif
// const isActiveRoute = (path) => {
// 	return route.path === path;
// };
</script>

<template>
	<!-- ======= Sidebar ======= -->
	<aside id="sidebar" class="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">
			<li class="nav-item">
				<a class="nav-link" href="/stocks">
					<i class="bi bi-basket3"></i>
					<span>Stock</span>
				</a>
			</li>
			<!-- End Dashboard Nav -->

			<li class="nav-item">
				<a class="nav-link" href="/request-approval">
					<i class="bi bi-database-exclamation"></i>
					<span>Req. Approval</span>
				</a>
			</li>
			<!-- End Dashboard Nav -->

			<li class="nav-heading">Admin</li>

			<li class="nav-item">
				<a class="nav-link" href="/">
					<i class="bi bi-speedometer"></i>
					<span>Dashboard</span>
				</a>
			</li>
			<!-- End Dashboard Nav -->

			<li class="nav-item">
				<a class="nav-link" href="/users">
					<i class="bi bi-person-gear"></i>
					<span>User</span>
				</a>
			</li>
			<!-- End Dashboard Nav -->
		</ul>
	</aside>
	<!-- End Sidebar-->
</template>
