<script setup lang="ts">
import { defineProps, computed } from "vue";

interface Props {
	message?: string;
	type?: "success" | "error" | "warning" | "info";
}

const props = withDefaults(defineProps<Props>(), {
	type: "success",
});

const alertClass = computed(() => {
	const classes: Record<string, string> = {
		success: "alert-success",
		error: "alert-danger",
		warning: "alert-warning",
		info: "alert-info",
	};
	return classes[props.type] || "alert-success";
});

const alertIcon = computed(() => {
	const icons: Record<string, string> = {
		success: "bi-check-circle",
		error: "bi-exclamation-circle",
		warning: "bi-exclamation-triangle",
		info: "bi-info-circle",
	};
	return icons[props.type] || "bi-check-circle";
});
</script>

<template>
	<div
		v-if="message"
		:class="['alert', alertClass, 'alert-dismissible', 'fade', 'show']"
		role="alert"
	>
		<i :class="['bi', alertIcon, 'me-2']"></i>
		{{ message }}
		<button
			type="button"
			class="btn-close"
			data-bs-dismiss="alert"
			aria-label="Close"
		></button>
	</div>
</template>
