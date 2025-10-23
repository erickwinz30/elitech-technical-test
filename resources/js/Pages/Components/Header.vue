<script setup></script>

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
						<span class="d-none d-md-block dropdown-toggle ps-2"
							>Test Admin</span
						>
					</a>
					<!-- End Profile Image Icon -->

					<ul
						class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
					>
						<li class="dropdown-header">
							<h6>Test Admin</h6>
							<span>Administrator</span>
						</li>
						<li>
							<hr class="dropdown-divider" />
						</li>

						<li>
							<a
								class="dropdown-item d-flex align-items-center"
								href="/logout"
								onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
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

		<form id="logout-form" action="/logout" method="POST" style="display: none">
			<!-- Add CSRF token if needed -->
		</form>
	</header>
	<!-- End Header -->
</template>
