<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengurus_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('jabatan', 100);
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('prestasi_alumni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->string('nama_prestasi', 255);
            $table->string('tingkat', 100)->nullable();
            $table->year('tahun_perolehan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('sertifikat_url')->nullable();
            $table->timestamps();
        });

        Schema::create('testimonies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
            $table->text('pesan');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('key_identifier', 100)->unique();
            $table->string('judul', 255)->nullable();
            $table->longText('isi')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
        Schema::dropIfExists('testimonies');
        Schema::dropIfExists('prestasi_alumni');
        Schema::dropIfExists('pengurus_alumni');
    }
};
