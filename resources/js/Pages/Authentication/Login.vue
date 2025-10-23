<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";

// Define props to receive flash messages from Laravel
const props = defineProps({
	errors: Object,
	flash: {
		type: Object,
		default: () => ({}),
	},
});

// Create form using Inertia's useForm
const form = useForm({
	username: "",
	password: "",
});

// Submit function
const submit = () => {
	form.post("/login", {
		onFinish: () => {
			// Reset password field on finish (success or error)
			form.password = "";
		},
	});
};

// Computed property for flash messages
const successMessage = computed(() => props.flash?.success || null);
const errorMessage = computed(() => props.flash?.error || null);
</script>

<template>
	<main>
		<div class="container">
			<section
				class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4"
			>
				<!-- Success Alert -->
				<div v-if="successMessage" class="row justify-content-center">
					<div
						class="alert alert-success alert-dismissible fade show col-lg-12 justify-content-center"
						role="alert"
					>
						{{ successMessage }}
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="alert"
							aria-label="Close"
						></button>
					</div>
				</div>

				<!-- Error Alert -->
				<div v-if="errorMessage" class="row justify-content-center">
					<div
						class="alert alert-danger alert-dismissible fade show col-lg-12 justify-content-center"
						role="alert"
					>
						{{ errorMessage }}
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="alert"
							aria-label="Close"
						></button>
					</div>
				</div>

				<div class="container">
					<div class="row justify-content-center">
						<div
							class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center"
						>
							<div class="d-flex justify-content-center py-4">
								<a href="/" class="logo d-flex align-items-center w-auto">
									<img
										src="/images/elitech_logo.jpg"
										alt="Elitech Logo"
										style="max-height: 6em"
									/>
								</a>
							</div>
							<!-- End Logo -->

							<div class="card mb-3">
								<div class="card-body">
									<div class="pt-4 pb-2">
										<h5 class="card-title text-center pb-0 fs-4">
											Login to Your Account
										</h5>
										<p class="text-center small">
											Masukkan username & password untuk login
										</p>
									</div>

									<form
										@submit.prevent="submit"
										class="row g-3 needs-validation"
									>
										<!-- Username Field -->
										<div class="col-12">
											<label for="username" class="form-label">Username</label>
											<div class="input-group has-validation">
												<span
													class="input-group-text"
													id="inputGroupPrepend"
													style="border-radius: 5px 0 0 5px"
													>@</span
												>
												<input
													type="text"
													v-model="form.username"
													name="username"
													class="form-control"
													:class="{ 'is-invalid': form.errors.username }"
													id="username"
													placeholder="Masukkan username..."
													required
												/>
												<div
													v-if="form.errors.username"
													class="invalid-feedback"
												>
													{{ form.errors.username }}
												</div>
											</div>
										</div>

										<!-- Password Field -->
										<div class="col-12">
											<label for="yourPassword" class="form-label"
												>Password</label
											>
											<input
												type="password"
												v-model="form.password"
												name="password"
												class="form-control"
												:class="{ 'is-invalid': form.errors.password }"
												id="yourPassword"
												placeholder="Masukkan password..."
												required
											/>
											<div v-if="form.errors.password" class="invalid-feedback">
												{{ form.errors.password }}
											</div>
										</div>

										<!-- Submit Button -->
										<div class="col-12">
											<button
												class="btn btn-primary w-100"
												type="submit"
												:disabled="form.processing"
											>
												<span v-if="form.processing">
													<span
														class="spinner-border spinner-border-sm me-2"
														role="status"
														aria-hidden="true"
													></span>
													Loading...
												</span>
												<span v-else>Login</span>
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</main>
	<!-- End #main -->

	<a
		href="#"
		class="back-to-top d-flex align-items-center justify-content-center"
		><i class="bi bi-arrow-up-short"></i
	></a>
</template>
