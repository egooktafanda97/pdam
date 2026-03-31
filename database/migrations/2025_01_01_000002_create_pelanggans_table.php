<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('nomor_pelanggan', 50)->unique();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('nomor_meter', 50)->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->foreignId('golongan_tarif_id')->constrained('golongan_tarif')->restrictOnDelete();
            $table->string('foto_ktp', 100)->nullable();
            $table->string('koordinat', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
