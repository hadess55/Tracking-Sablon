<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('produksi_logs', function (Blueprint $t) {
      $t->id();
      $t->foreignId('produksi_id')->constrained('produksi')->cascadeOnDelete();
      $t->string('status_key');       
      $t->text('catatan')->nullable();
      $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('produksi_logs'); }
};
