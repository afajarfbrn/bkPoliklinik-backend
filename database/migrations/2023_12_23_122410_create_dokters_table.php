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
        Schema::create('dokter', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('alamat');
            $table->string('no_hp');
            $table->uuid('id_poli')->nullable();
            $table->string("role");
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('id_poli')->references('id')->on('poli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
