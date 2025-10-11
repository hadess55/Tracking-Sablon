<?php

// database/migrations/xxxx_xx_xx_create_produksis_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('produksi', function (Blueprint $t) {
      $t->id();
      $t->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
      $t->string('nomor_resi')->unique();   
      $t->string('status_key');            
      $t->unsignedTinyInteger('progress')->nullable(); 
      $t->text('catatan')->nullable();
      $t->timestamp('mulai_at')->nullable();
      $t->timestamp('selesai_at')->nullable();
      $t->timestamps();
      $t->index('status_key');
    });
  }
  public function down(): void { Schema::dropIfExists('produksi'); }
};
