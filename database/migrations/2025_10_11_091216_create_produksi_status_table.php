<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('produksi_status', function (Blueprint $t) {
      $t->id();
      $t->string('key')->unique(); 
      $t->string('label');    
      $t->unsignedInteger('urutan')->default(0);
      $t->boolean('is_final')->default(false);
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('produksi_status'); }
};

