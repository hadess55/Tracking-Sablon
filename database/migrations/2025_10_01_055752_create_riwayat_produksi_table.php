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
        Schema::create('riwayat_produksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')
                  ->constrained('produksi')
                  ->cascadeOnDelete();

            $table->string('tahapan');
            $table->text('catatan')->nullable();
            $table->timestamp('dilakukan_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_produksi');
    }
};
