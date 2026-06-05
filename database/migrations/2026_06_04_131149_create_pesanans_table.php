<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique(); // PSN-2026-001
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->date('tanggal_pesanan');
            $table->enum('status', ['diproses', 'dikerjakan', 'selesai'])->default('diproses');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
