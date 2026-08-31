<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angkatan', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_angkatan', 10);
            $table->string('nama_angkatan', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angkatan');
    }
};
