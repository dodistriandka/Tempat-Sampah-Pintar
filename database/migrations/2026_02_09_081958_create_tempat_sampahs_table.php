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
    Schema::create('tempat_sampahs', function (Blueprint $table) {
        $table->id();
        $table->string('kode_tempat')->unique(); // TS001
        $table->string('lokasi');
        $table->integer('kapasitas_cm');
        $table->enum('status', ['kosong', 'hampir_penuh', 'penuh'])
              ->default('kosong');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat_sampahs');
    }
};
