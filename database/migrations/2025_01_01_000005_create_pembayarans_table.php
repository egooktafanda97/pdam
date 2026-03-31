<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan')->cascadeOnDelete();
            $table->dateTime('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->string('metode_bayar', 50);
            $table->string('penyedia_layanan', 50)->nullable();
            $table->string('kode_pembayaran', 255)->nullable();
            $table->enum('status_pembayaran', ['Pending', 'Sukses', 'Gagal', 'Kedaluwarsa'])
                  ->default('Sukses');
            $table->string('referensi_gateway', 100)->unique()->nullable();
            $table->string('bukti_pembayaran', 100)->unique()->nullable();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
