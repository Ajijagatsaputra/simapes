# SIMAPES (Sistem Informasi Manajemen Pemesanan Konveksi & Prediksi)

SIMAPES adalah sistem informasi berbasis web yang dirancang khusus untuk mempermudah pemilik/pengelola jasa konveksi (khususnya pembuatan seragam sekolah/organisasi) dalam mengelola seluruh siklus bisnis pemesanan pakaian secara terkomputerisasi. Selain manajemen operasional, sistem ini dilengkapi dengan modul **Sistem Prediksi** untuk meramalkan jumlah pemesanan di masa mendatang guna membantu pengambilan keputusan pengadaan bahan baku (MRP) dan alokasi kapasitas produksi.

Aplikasi ini dikembangkan menggunakan arsitektur **MVC (Model-View-Controller)** yang bersih, terstruktur, dan terintegrasi penuh dengan database.

---

## 👥 Aktor Sistem

Untuk efisiensi dan fokus kebutuhan Tugas Akhir (TA) / Skripsi, sistem ini dirancang dengan **1 Aktor Utama (Admin-Only)** sebagai sistem pengelolaan internal (_Back-Office_). Admin bertanggung jawab menginput data pelanggan, mengelola katalog produk, mencatat pesanan masuk, memperbarui status pengerjaan, melakukan simulasi kalkulasi prediksi penjualan, hingga mengelola rantai pasok bahan baku.

---

## 🚀 Fitur Utama

1. **Dashboard Interaktif**:
    - Menampilkan card ringkasan statistik real-time (Total Pelanggan, Total Produk, Total Pesanan, Pesanan Sedang Diproses, dan Pesanan Selesai).
    - **Grafik Peramalan Holt-Winters Terintegrasi**: Menggantikan data dummy dengan hasil kalkulasi peramalan riil yang ditarik dari database atau session data upload.
    - **UI Fallback Warning**: Deteksi dini jika data transaksi kurang dari 24 bulan, menampilkan peringatan UI non-teknis yang informatif alih-alih merusak grafik chart.

2. **Manajemen Data Pelanggan**:
    - Fitur CRUD lengkap untuk mencatat profil pelanggan (Nama, Email, WhatsApp, Alamat, dan Nama Sekolah/Instansi).

3. **Manajemen Data Produk**:
    - Pencatatan katalog produk seragam konveksi beserta tipe, harga, dan stok bahan baku.
    - Deteksi otomatis status stok (_Low Stock_ & _Empty_).

4. **Manajemen Transaksi Pesanan**:
    - Pembuatan invoice pesanan dengan nomor pesanan unik yang dihasilkan otomatis (`PSN-YYYYMMDD-XXXX`).
    - Alur status pengerjaan bertahap: `Diproses` (Pesanan masuk) ➔ `Dikerjakan` (Penjahitan) ➔ `Selesai` (Siap diambil/dikirim).
    - Detail pesanan multi-item beserta ukuran seragam (S, M, L, XL).

5. **Sistem Prediksi Holt-Winters (Fitur Unggulan TA)**:
    - Menggunakan algoritma **Triple Exponential Smoothing (Holt-Winters Multiplicative)** riil untuk meramal 12 bulan ke depan.
    - **Form Parameter Dinamis**: Admin dapat memasukkan nilai parameter pemulusan Alpha ($\alpha$), Beta ($\beta$), dan Gamma ($\gamma$) secara interaktif.
    - **Evaluasi Error (MAPE & MAD)**: Menampilkan tingkat kesalahan ramalan secara matematis guna menguji keandalan hasil prediksi.
    - **Visualisasi Chart.js**: Grafik kesinambungan kontinu antara data aktual (3 tahun lalu) dan data proyeksi (1 tahun mendatang).
    - **Dual-Mode Data Ingestion**: Sistem mampu menghitung prediksi secara otomatis dari database sistem atau via unggah (upload) file CSV/Excel data historis secara fleksibel.

6. **Analisis Cerdas & Rekomendasi AI**:
    - Terintegrasi dengan **Gemini API (Direct)** dan **OpenRouter (Gemini 2.5 Flash)**.
    - AI membaca hasil peramalan Holt-Winters dan kalkulasi MRP untuk memberikan analisis tren taktis, rekomendasi pengadaan, dan strategi operasional konveksi secara instan.
    - Dilengkapi _circuit breaker_ parameter `max_tokens` (2048) untuk menjamin pemakaian yang hemat kuota pada limitasi gratis API.

7. **Perencanaan Kebutuhan Bahan Baku (MRP & SCM)**:
    - Menghitung kebutuhan bahan mentah (Kain, Kancing, Benang, Resleting) untuk menyelesaikan pesanan ramalan 12 bulan ke depan.
    - **Safety Stock (Stok Pengaman)** & **Reorder Point (ROP)** dihitung secara dinamis berbasis data lead-time pengiriman supplier.

8. **Simulator Skenario "What-If" Interaktif (Baru)**:
    - Slider simulasi di browser untuk memodelkan skenario pasar (-30% Krisis s.d +50% Lonjakan Puncak).
    - Tabel MRP, Safety Stock, ROP, isi draf chat WhatsApp Supplier, dan link Cetak PO berubah otomatis secara dinamis (_real-time_) mengikuti opsi skenario yang dipilih.

9. **Ekspor Laporan PDF Komprehensif (Baru)**:
    - Menghasilkan berkas cetak PDF resmi menggunakan `dompdf` berisi data evaluasi model peramalan (MAPE/MAD), tabel proyeksi 12 bulan, tabel perhitungan MRP/ROP, dan hasil analisis narasi AI.

---

## 📊 Detail Teknis Algoritma Prediksi (Bahan Sidang TA)

### Mengapa Holt-Winters Multiplikatif?

Data pesanan jasa konveksi seragam memiliki karakteristik **Tren** jangka panjang sekaligus **Musiman** tahunan yang sangat kuat (misalnya: pesanan selalu melonjak tajam menjelang Tahun Ajaran Baru sekolah di bulan Juni - Agustus, dan sepi di bulan Desember). Metode Holt-Winters Multiplikatif sangat cocok karena mampu memperhitungkan komponen musiman yang bersifat proporsional terhadap rata-rata level data.

### Persamaan Matematis yang Digunakan:

1. **Level ($L_t$)**: $L_t = \alpha \frac{Y_t}{S_{t-12}} + (1 - \alpha) (L_{t-1} + T_{t-1})$
2. **Trend ($T_t$)**: $T_t = \beta (L_t - L_{t-1}) + (1 - \beta) T_{t-1}$
3. **Seasonal ($S_t$)**: $S_t = \gamma \frac{Y_t}{L_t} + (1 - \gamma) S_{t-12}$
4. **Proyeksi**: $F_{t+m} = (L_t + m \cdot T_t) \cdot S_{t-12+m}$

---

## 🛠️ Langkah Instalasi & Penggunaan

### Metode A: Menggunakan Docker (Direkomendasikan)

Aplikasi telah dilengkapi konfigurasi Docker Compose ter-orkestrasi:

1. **Clone & Masuk ke Proyek**:

    ```bash
    cd simapes
    ```

2. **Konfigurasi Environment**:
    - Salin `.env.example` menjadi `.env`
    - Pastikan konfigurasi database mengarah ke service kontainer MySQL (`db`):
        ```env
        DB_HOST=db
        DB_PORT=3306
        DB_DATABASE=simapes
        DB_USERNAME=root
        DB_PASSWORD=
        ```

3. **Jalankan Docker Compose**:

    ```bash
    docker compose up -d --build
    ```

4. **Instal Dependencies**:

    ```bash
    docker compose exec app composer install
    docker compose exec app npm install
    ```

5. **Generate Application Key & Izin Folder**:

    ```bash
    docker compose exec app php artisan key:generate
    docker compose exec app chmod -R 777 storage bootstrap/cache
    ```

6. **Jalankan Migrasi & Suntik Data Historis 3 Tahun**:

    ```bash
    docker compose exec app php artisan migrate:fresh --seed
    ```

7. **Jalankan Vite Dev Server**:

    ```bash
    docker compose exec app npm run dev
    ```

8. **Akses Aplikasi**:
   Buka **[http://localhost:8091](http://localhost:8091)** di browser Anda.
    - **Email**: `admin@gmail.com`
    - **Password**: `12345678`

---

### Metode B: Instalasi Lokal (Tanpa Docker)

1. **Install Dependencies**:

    ```bash
    composer install
    npm install
    ```

2. **Konfigurasi Database**:
   Sesuaikan file `.env` ke database lokal MySQL Anda (`DB_HOST=127.0.0.1`).

3. **Jalankan Migrasi & Seeder**:

    ```bash
    php artisan migrate:fresh --seed
    ```

4. **Jalankan Server & Aset Compile**:
    ```bash
    php artisan serve
    npm run dev
    ```
    Buka `http://127.0.0.1:8000` di browser.

---

## 📁 Struktur Folder Utama Proyek (MVC & Services)

- **`app/Http/Controllers/`**
    - `DashboardController.php` - Mengelola data statistik dashboard & memicu Holt-Winters ke beranda utama.
    - `PrediksiController.php` - Menerima input $\alpha, \beta, \gamma$, memicu prediksi/MRP, menyimpan riwayat analisis AI di session, dan mencetak laporan PO/PDF.
- **`app/Services/`**
    - `PredictionService.php` - Logika Holt-Winters, perhitungan MAPE/MAD, optimasi parameter otomatis, dan kalkulasi Safety Stock/ROP.
    - `AiPredictionService.php` - Gateway komunikasi API AI ke Gemini dan OpenRouter dengan penanganan token dinamis.
- **`resources/views/admin/prediksi/`**
    - `index.blade.php` - Interface peramalan utama.
    - `pdf_report.blade.php` - Template cetak PDF laporan formal.
    - `partials/mrp.blade.php` - Tabel perencanaan bahan & panel simulator "What-if".
    - `partials/supplier.blade.php` - Daftar rekomendasi supplier terintegrasi PO & WA.
    - `partials/scripts.blade.php` - Client-side simulator engine & local markdown parser.
