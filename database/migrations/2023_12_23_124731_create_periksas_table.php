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
        Schema::create('periksa', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_daftar_poli');
            $table->dateTime('tanggal')->nullable();
            $table->text('catatan')->nullable();
            $table->integer('biaya_periksa')->nullable();

            $table->foreign('id_daftar_poli')->references('id')->on('daftar_poli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periksa');
    }
};
