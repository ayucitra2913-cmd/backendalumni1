<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('angkatan_id')->nullable()->constrained('angkatan')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            $table->string('nisn', 20)->nullable();
            $table->string('nama_lengkap', 150);
            $table->enum('jenis_kelamin', ['L', 'P'])->default('L');
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('pekerjaan_saat_ini', 200)->nullable();
            $table->string('foto_profil')->default('https://i.pravatar.cc/150');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
