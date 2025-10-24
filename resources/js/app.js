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

// datatables
import jQuery from "jquery";
import jszip from "jszip";
import pdfmake from "pdfmake";
import DataTable from "datatables.net-vue3";
import DataTablesCore from "datatables.net-bs5";
import "datatables.net-buttons-bs5";
import "datatables.net-buttons/js/buttons.colVis.mjs";
import "datatables.net-buttons/js/buttons.html5.mjs";
import "datatables.net-columncontrol-bs5";
import DateTime from "datatables.net-datetime";
import "datatables.net-keytable-bs5";
import "datatables.net-responsive-bs5";
import "datatables.net-rowgroup-bs5";
import "datatables.net-scroller-bs5";
import "datatables.net-searchbuilder-bs5";
import "datatables.net-searchpanes-bs5";
import "datatables.net-select-bs5";

//sweetalert2
import Swal from "sweetalert2";

DataTablesCore.Buttons.jszip(jszip);
DataTablesCore.Buttons.pdfMake(pdfmake);
DataTable.use(DataTablesCore);

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
