<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('progres_produksis', function (Blueprint $table) {
            // Nomor urut tahapan tetap (1–5)
            $table->unsignedTinyInteger('tahapan_ke')->default(0)->after('pesanan_id');
            // Timestamp saat tahap ditandai selesai
            $table->timestamp('selesai_pada')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('progres_produksis', function (Blueprint $table) {
            $table->dropColumn(['tahapan_ke', 'selesai_pada']);
        });
    }
};
