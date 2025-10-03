<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('nomor_hp', 30)->nullable()->index();

            // Data alamat (opsional)
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos', 10)->nullable();

            // Status persetujuan
            // nilai: menunggu | disetujui | ditolak
            $table->enum('status_persetujuan', ['menunggu', 'disetujui', 'ditolak'])
                  ->default('menunggu')
                  ->index();

            // Jejak persetujuan
            $table->foreignId('disetujui_oleh')  // user admin yang menyetujui
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('disetujui_pada')->nullable();

            // Keamanan dasar
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // kalau mau arsip tanpa benar2 menghapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
