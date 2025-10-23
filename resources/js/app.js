// Local vendor assets (from resources/vendor) - imported so Vite will bundle them
import "../vendor/bootstrap/css/bootstrap.min.css";
import "../vendor/bootstrap-icons/bootstrap-icons.css";
import "../vendor/boxicons/css/boxicons.min.css";
import "../vendor/quill/quill.snow.css";
import "../vendor/quill/quill.bubble.css";
import "../vendor/remixicon/remixicon.css";
import "../vendor/simple-datatables/style.css";

// Main application CSS (NiceAdmin template styles)
import "../css/app.css";

import "./bootstrap"; // bootstrap setup (Laravel default)
import "../vendor/bootstrap/js/bootstrap.bundle.min.js"; // includes Popper

// Other vendor JS
import "../vendor/apexcharts/apexcharts.min.js";
import "../vendor/chart.js/chart.umd.js";
import "../vendor/echarts/echarts.min.js";
import "../vendor/quill/quill.js";
import "../vendor/simple-datatables/simple-datatables.js";
import "../vendor/tinymce/tinymce.min.js";
import "../vendor/php-email-form/validate.js";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

// Sidebar toggle functionality
document.addEventListener("DOMContentLoaded", () => {
	const toggleSidebarBtn = document.querySelector(".toggle-sidebar-btn");
	if (toggleSidebarBtn) {
		toggleSidebarBtn.addEventListener("click", () => {
			document.body.classList.toggle("toggle-sidebar");
		});
	}
});

createInertiaApp({
	title: (title) => `Aplikasi Saya - ${title}`, // Opsional: Mengatur judul halaman

	resolve: (name) => {
		// Fungsi ini memberi tahu Inertia cara memuat komponen halaman Vue
		const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
		return pages[`./Pages/${name}.vue`];
	},

	setup({ el, App, props, plugin }) {
		return (
			createApp({ render: () => h(App, props) })
				.use(plugin)
				// .use(ZiggyVue) // Opsional: jika Anda butuh routing Laravel di JS
				.mount(el)
		);
	},

	progress: {
		// Menampilkan progress bar saat halaman dimuat
		color: "#4B5563",
	},
});
