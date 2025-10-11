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
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'judul')) {
                $table->renameColumn('judul', 'produk');
            }
            $table->string('bahan')->nullable()->after('deskripsi');
            $table->string('warna')->nullable()->after('bahan');
            $table->json('ukuran_kaos')->nullable()->after('warna');
            $table->string('tautan_drive')->nullable()->after('ukuran_kaos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'produk')) {
                $table->renameColumn('produk', 'judul');
            }
            $table->dropColumn(['bahan','warna','ukuran_kaos','tautan_drive']);
        });
    }
};
