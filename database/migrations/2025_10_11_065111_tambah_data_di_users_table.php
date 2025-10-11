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
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->unique()->after('email');
            $table->string('jalan')->nullable()->after('no_hp');
            $table->string('rt', 5)->nullable()->after('jalan');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('kelurahan')->nullable()->after('rw');
            $table->string('kecamatan')->nullable()->after('kelurahan');
            $table->string('kota_kab')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kota_kab');
            $table->string('kode_pos', 10)->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['no_hp']);
            $table->dropColumn([
                'jalan','rt','rw','kelurahan','kecamatan','kota_kab','provinsi','kode_pos'
            ]);
        });
    }
};
