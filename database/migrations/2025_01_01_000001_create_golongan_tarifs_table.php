<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('golongan_tarif', function (Blueprint $table) {
            $table->id();
            $table->string('kode_golongan', 20)->unique();
            $table->string('nama_golongan', 100);
            $table->decimal('tarif_per_m3', 15, 2);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golongan_tarif');
    }
};
