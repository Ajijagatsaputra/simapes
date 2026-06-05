# SIMAPES (Sistem Informasi Manajemen Pemesanan Konveksi & Prediksi)

SIMAPES adalah sistem informasi berbasis web yang dirancang khusus untuk mempermudah pemilik/pengelola jasa konveksi (khususnya pembuatan seragam sekolah/organisasi) dalam mengelola seluruh siklus bisnis pemesanan pakaian secara terkomputerisasi. Selain manajemen operasional, sistem ini dilengkapi dengan modul **Sistem Prediksi** untuk meramalkan jumlah pemesanan di masa mendatang guna membantu pengambilan keputusan pengadaan bahan baku dan alokasi tenaga penjahit.

Aplikasi ini dikembangkan menggunakan arsitektur **MVC (Model-View-Controller)** yang bersih, terstruktur, dan terintegrasi penuh dengan database.

---

## 👥 Aktor Sistem
Untuk efisiensi dan fokus kebutuhan Tugas Akhir (TA), sistem ini dirancang dengan **1 Aktor Utama (Admin-Only)** sebagai sistem pengelolaan internal (*Back-Office*). Admin bertanggung jawab menginput data pelanggan, mengelola katalog produk, mencatat pesanan masuk, memperbarui status pengerjaan, dan melakukan simulasi kalkulasi prediksi penjualan.

---

## 🚀 Fitur Utama

1. **Dashboard Interaktif**:
   - Menampilkan card ringkasan statistik real-time (Total Pelanggan, Total Produk, Total Pesanan, Pesanan Sedang Diproses, dan Pesanan Selesai).
   - Grafik garis visualisasi jumlah pesanan bulanan aktual.
   - Tabel ringkasan 5 transaksi pesanan terbaru.

2. **Manajemen Data Pelanggan**:
   - Fitur CRUD lengkap untuk mencatat profil pelanggan (Nama, Email, WhatsApp, Alamat, dan Nama Sekolah/Instansi).

3. **Manajemen Data Produk**:
   - Pencatatan katalog produk seragam konveksi beserta tipe, harga, dan stok bahan baku.
   - Deteksi otomatis status stok (*Low Stock* & *Empty*).

4. **Manajemen Transaksi Pesanan**:
   - Pembuatan invoice pesanan dengan nomor pesanan unik yang dihasilkan otomatis (`PSN-YYYYMMDD-XXXX`).
   - Alur status pengerjaan bertahap: `Diproses` (Pesanan masuk) ➔ `Dikerjakan` (Penjahitan) ➔ `Selesai` (Siap diambil/dikirim).
   - Detail pesanan multi-item beserta ukuran seragam (S, M, L, XL).

5. **Sistem Prediksi Holt-Winters (Fitur Unggulan TA)**:
   - Menggunakan algoritma **Triple Exponential Smoothing (Holt-Winters Multiplicative)** riil untuk meramal 12 bulan ke depan.
   - **Form Parameter Dinamis**: Admin dapat memasukkan nilai parameter pemulusan Alpha ($\alpha$), Beta ($\beta$), dan Gamma ($\gamma$) secara interaktif untuk mencari tingkat akurasi tertinggi secara langsung.
   - **Evaluasi Error (MAPE & MAD)**: Menampilkan tingkat kesalahan ramalan secara matematis guna menguji keandalan hasil prediksi.
   - **Visualisasi Chart.js**: Grafik kesinambungan kontinu antara data aktual (3 tahun lalu) dan data proyeksi (1 tahun mendatang).
   - **Detail Rumus Akademik**: Bagian penjelasan rumus matematika pemulusan level, tren, dan musiman untuk mempermudah sidang pertanggungjawaban di depan dosen penguji.

---

## 📊 Detail Teknis Algoritma Prediksi (Bahan Sidang TA)

### Mengapa Holt-Winters Multiplikatif?
Data pesanan jasa konveksi seragam memiliki karakteristik **Tren** jangka panjang sekaligus **Musiman** tahunan yang sangat kuat (misalnya: pesanan selalu melonjak tajam menjelang Tahun Ajaran Baru sekolah di bulan Juni - Agustus, dan sepi di bulan Desember). Metode Holt-Winters Multiplikatif sangat cocok karena mampu memperhitungkan komponen musiman yang bersifat proporsional terhadap rata-rata level data.

### Cara Kerja Perhitungan di Sistem
Prediksi dihitung secara langsung di backend server (**`App\Services\PredictionService`**) secara *on-the-fly* (langsung dihitung saat halaman dimuat) dari database riil tabel `pesanans`. **Tidak ada penyimpanan file model Machine Learning terpisah**.

1. **Inisialisasi**: Sistem menghitung nilai Level awal ($L_0$), Tren awal ($T_0$), dan 12 Indeks Musiman ($S_1 \dots S_{12}$) berdasarkan data 24 bulan pertama (2 tahun awal).
2. **Iterasi Pemulusan (Smoothing)**: Sistem menyusuri data dari bulan ke-13 hingga bulan ke-36 untuk memperbarui parameter level, tren, dan musiman dengan persamaan:
   - **Level ($L_t$)**: $L_t = \alpha \frac{Y_t}{S_{t-12}} + (1 - \alpha) (L_{t-1} + T_{t-1})$
   - **Trend ($T_t$)**: $T_t = \beta (L_t - L_{t-1}) + (1 - \beta) T_{t-1}$
   - **Seasonal ($S_t$)**: $S_t = \gamma \frac{Y_t}{L_t} + (1 - \gamma) S_{t-12}$
3. **Proyeksi**: Hasil peramalan untuk $m$ bulan ke depan dihitung dengan rumus: $F_{t+m} = (L_t + m \cdot T_t) \cdot S_{t-12+m}$

---

## 🛠️ Langkah Instalasi & Penggunaan

Pilih salah satu metode instalasi di bawah ini:

### Metode A: Instalasi Lokal (Tanpa Docker)

1. **Clone & Masuk ke Proyek**:
   ```bash
   cd simapes
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   - Salin file `.env.example` menjadi `.env`
   - Sesuaikan nama database di bagian `DB_DATABASE=simapes` dan pastikan `DB_HOST=127.0.0.1`

4. **Jalankan Migrasi & Suntik Data Historis 3 Tahun**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```

6. **Akun Akses Default**:
   Buka `http://127.0.0.1:8000/` di browser dan masuk menggunakan akun admin berikut:
   - **Email**: `admin@gmail.com`
   - **Password**: `12345678`

---

### Metode B: Menggunakan Docker (Rekomendasi)

Jika Anda ingin menjalankan aplikasi di dalam kontainer Docker tanpa perlu menginstal PHP, Composer, Node.js, atau MySQL secara lokal:

1. **Clone & Masuk ke Proyek**:
   ```bash
   cd simapes
   ```

2. **Konfigurasi Environment**:
   - Salin file `.env.example` menjadi `.env` (jika belum ada).
   - Sesuaikan konfigurasi database agar terhubung ke kontainer MySQL di dalam Docker:
     ```env
     DB_HOST=db
     DB_PORT=3306
     DB_DATABASE=simapes
     DB_USERNAME=root
     DB_PASSWORD=
     ```

3. **Jalankan Docker Compose**:
   Bangun dan jalankan kontainer di background:
   ```bash
   docker compose up -d --build
   ```

4. **Install Dependencies di Dalam Kontainer**:
   ```bash
   docker compose exec app composer install
   docker compose exec app npm install
   ```

5. **Atur Izin Folder (Permissions)**:
   Berikan akses tulis untuk folder storage dan cache di dalam kontainer:
   ```bash
   docker compose exec app chmod -R 777 storage bootstrap/cache
   ```

6. **Generate Application Key**:
   ```bash
   docker compose exec app php artisan key:generate
   ```

7. **Jalankan Migrasi & Suntik Data Historis**:
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

8. **Compile Aset Frontend (Vite)**:
   - Untuk mode pengembangan (Hot Reloading / Dev):
     ```bash
     docker compose exec app npm run dev
     ```
   - Untuk membuild aset produksi:
     ```bash
     docker compose exec app npm run build
     ```

9. **Akun Akses Default**:
   Buka `http://localhost:8000/` di browser dan masuk menggunakan akun admin berikut:
   - **Email**: `admin@gmail.com`
   - **Password**: `12345678`

10. **Menghentikan Kontainer**:
    ```bash
    docker compose down
    ```

---

## 📁 Struktur Folder Utama Proyek (MVC)
*   **`app/Http/Controllers/`**
    *   `DashboardController.php` - Mengelola data statistik & ringkasan di beranda utama.
    *   `PrediksiController.php` - Menerima parameter input $\alpha, \beta, \gamma$, memanggil layanan prediksi, dan mengirim data ke frontend.
*   **`app/Services/`**
    *   `PredictionService.php` - Berisi implementasi rumus matematika Holt-Winters, perhitungan MAPE, MAD, dan inisialisasi musiman.
*   **`database/seeders/`**
    *   `DatabaseSeeder.php` - Seeder master data admin, pelanggan, dan produk.
    *   `PesananHistorisSeeder.php` - Menghasilkan data transaksi penjualan musiman 3 tahun (36 data point bulanan) secara dinamis.
*   **`resources/views/`**
    *   `layouts/main.blade.php` - Layout master admin (Sidebar, Notifikasi Toast, & Modal Konfirmasi).
    *   `dashboard.blade.php` - Tampilan beranda utama admin.
    *   `prediksi/index.blade.php` - Halaman form interaktif parameter, grafik Chart.js, tabel ramalan, dan rincian rumus.
