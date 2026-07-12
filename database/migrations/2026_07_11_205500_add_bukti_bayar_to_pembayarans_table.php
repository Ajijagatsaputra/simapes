<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('pembayarans', 'bukti_bayar')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                $table->string('bukti_bayar')->nullable()->after('catatan');
                $table->text('catatan_pelanggan')->nullable()->after('bukti_bayar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pembayarans', 'bukti_bayar')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                $table->dropColumn(['bukti_bayar', 'catatan_pelanggan']);
            });
        }
    }
};