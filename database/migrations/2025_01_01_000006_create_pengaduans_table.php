<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->string('judul_pengaduan', 150);
            $table->string('kategori', 50);
            $table->text('deskripsi');
            $table->dateTime('tanggal_pengaduan');
            $table->enum('status', ['Baru', 'Diproses', 'Selesai'])->default('Baru');
            $table->text('tanggapan')->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('tanggal_tanggapan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
