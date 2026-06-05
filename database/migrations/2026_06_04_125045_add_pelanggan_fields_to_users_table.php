<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_whatsapp', 20)->nullable()->after('email');
            $table->text('alamat')->nullable()->after('no_whatsapp');
            $table->string('nama_sekolah', 255)->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_whatsapp', 'alamat', 'nama_sekolah']);
        });
    }
};
