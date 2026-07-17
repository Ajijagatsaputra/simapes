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
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('subtotal');
            $table->string('path_gambar', 255)->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropColumn(['catatan', 'path_gambar']);
        });
    }
};
