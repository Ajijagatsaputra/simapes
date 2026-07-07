<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tambah kolom pembayaran di tabel pesanans
        Schema::table('pesanans', function (Blueprint $table) {
            $table->decimal('total_terbayar', 14, 2)->default(0)->after('total_harga');
            $table->decimal('sisa_tagihan', 14, 2)->default(0)->after('total_terbayar');
            $table->enum('status_pembayaran', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar')->after('sisa_tagihan');
        });

        // 2. Tambah kolom jumlah_terbayar per item di detail_pesanans
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->integer('jumlah_terbayar')->default(0)->after('total_item');
        });

        // 3. Tabel pembayarans (riwayat setiap termin pembayaran)
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->integer('termin_ke')->default(1);
            $table->decimal('jumlah_bayar', 14, 2);
            $table->date('tanggal_bayar');
            $table->string('metode_pembayaran', 50)->default('transfer'); // transfer, tunai, dll
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // 4. Tabel pembayaran_details (alokasi per-item dari setiap pembayaran)
        Schema::create('pembayaran_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->foreignId('detail_pesanan_id')->constrained('detail_pesanans')->onDelete('cascade');
            $table->integer('jumlah_cover'); // berapa pcs yang di-cover oleh pembayaran ini
            $table->decimal('nominal_cover', 14, 2); // jumlah_cover * harga_satuan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_details');
        Schema::dropIfExists('pembayarans');

        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropColumn('jumlah_terbayar');
        });

        Schema::table('pesanans', function (Blueprint $table) {
            $table->dropColumn(['total_terbayar', 'sisa_tagihan', 'status_pembayaran']);
        });
    }
};
