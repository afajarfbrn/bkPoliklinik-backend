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
        Schema::create('daftar_poli', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pasien');
            $table->uuid('id_jadwal');
            $table->text('keluhan')->nullable();
            $table->integer('no_antrian')->unsigned()->nullable();

            $table->foreign('id_pasien')->references('id')->on('pasien');
            $table->foreign('id_jadwal')->references('id')->on('jadwal_periksa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_poli');
    }
};
