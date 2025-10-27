# Production Management System - Elitech

Sistem manajemen produksi berbasis web menggunakan Laravel 10, Inertia.js, dan Vue 3 dengan TypeScript.

## Fitur Utama

### PPIC (Production Planning and Inventory Control)

- **Perencanaan Produksi**: Membuat, menyetujui, dan menolak rencana produksi
- **Manajemen Produk**: CRUD produk dengan soft delete
- **Persetujuan Manager**: Workflow approval untuk rencana produksi

### Production

- **Order Produksi**: Memulai dan menyelesaikan order produksi
- **Status Tracking**: Pending, In Progress, Completed
- **Production Logs**: Riwayat perubahan status produksi

### Fitur Umum

- **Authentication**: Role-based (Manager/Staff) dan Department-based (PPIC/Production)
- **DataTables**: Export ke PDF, Excel, Copy, dan Print
- **Filter**: Filter berdasarkan tanggal
- **Responsive Design**: Bootstrap 5 dengan NiceAdmin template

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- NPM atau Yarn
- MySQL/MariaDB
- Laragon (opsional, untuk development di Windows)

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/erickwinz30/elitech-technical-test.git
cd elitech-technical-test
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file .env.example
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elitech_production
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database dan Seeding

```bash
# Jalankan migrasi
php artisan migrate

# Jalankan seeder untuk data dummy
php artisan db:seed
```

Data user default setelah seeding:

| Username           | Password | Role    | Department |
| ------------------ | -------- | ------- | ---------- |
| manager_ppic       | password | Manager | PPIC       |
| manager_production | password | Manager | Production |
| staff_ppic         | password | Staff   | PPIC       |
| staff_production   | password | Staff   | Production |

### 6. Build Assets

```bash
# Development mode
npm run dev

# Production build
npm run build
```

### 7. Jalankan Server

```bash
# Jalankan Laravel development server
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

**Atau jika menggunakan Laragon**, aplikasi akan otomatis tersedia di:
`http://technical-test-elitech.test`

## Struktur Akses

### Manager

- ✅ Dapat mengakses menu PPIC dan Production
- ✅ Dapat menyetujui/menolak rencana produksi (Manager PPIC)
- ✅ Akses penuh ke semua fitur

### Staff PPIC

- ✅ Menu PPIC (Perencanaan, Daftar Produk)
- ❌ Tidak dapat mengakses menu Production
- ✅ Dapat membuat rencana produksi
- ❌ Tidak dapat menyetujui/menolak rencana

### Staff Production

- ✅ Menu Production (Order Produksi, Riwayat Order)
- ❌ Tidak dapat mengakses menu PPIC
- ✅ Dapat memulai dan menyelesaikan produksi

## Tech Stack

### Backend

- Laravel 10.x
- PHP 8.1+
- MySQL
- Inertia.js Server-side

### Frontend

- Vue 3 (Composition API)
- TypeScript
- Inertia.js Client-side
- Bootstrap 5.3.8
- DataTables.net-vue3
- SweetAlert2
- Vite

### Export Libraries

- pdfMake (PDF Export)
- JSZip (Excel Export)
- DataTables Buttons Extension

## Command Penting

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recreate database (hati-hati, akan menghapus semua data)
php artisan migrate:fresh --seed

# Build untuk production
npm run build

# Run tests
php artisan test
```

## Troubleshooting

### Error: SQLSTATE[HY000] [1045] Access denied

Pastikan konfigurasi database di `.env` sudah benar.

### Error: Vite manifest not found

Jalankan `npm run build` atau pastikan `npm run dev` sedang berjalan.

### DataTables export tidak muncul

Pastikan semua dependencies terinstall dengan `npm install` dan browser sudah di-refresh (Ctrl+Shift+R).

### Pagination tidak rata kanan

Clear browser cache dengan hard reload (Ctrl+Shift+R atau Ctrl+F5).

## Kontak

Untuk pertanyaan atau masalah, hubungi:

- Email: erickwinz1008@gmail.com
- GitHub: [erickwinz30](https://github.com/erickwinz30)

## License

Project ini dibuat untuk keperluan technical test dan bersifat open source.
