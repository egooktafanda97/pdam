<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->foreignId('pemakaian_air_id')->unique()->constrained('pemakaian_air')->cascadeOnDelete();
            $table->unsignedTinyInteger('periode_bulan');
            $table->unsignedSmallInteger('periode_tahun');
            $table->integer('jumlah_meter');
            $table->decimal('biaya_pemakaian', 15, 2);
            $table->decimal('biaya_admin', 15, 2);
            $table->decimal('total_tagihan', 15, 2);
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->enum('status', ['Belum Bayar', 'Pending', 'Lunas'])->default('Belum Bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
