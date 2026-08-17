<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->text('spesifikasi_bahan')->nullable()->after('deskripsi');
            $table->text('size_chart')->nullable()->after('spesifikasi_bahan');
            $table->text('estimasi_bb_tb')->nullable()->after('size_chart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropColumn(['spesifikasi_bahan', 'size_chart', 'estimasi_bb_tb']);
        });
    }
};
