<script setup>
import Header from "../Components/Header.vue";
import Sidebar from "../Components/Sidebar.vue";
</script>

<template>
	<Header />
	<Sidebar />
	<main id="main" class="main">
		<slot />
	</main>
</template>
