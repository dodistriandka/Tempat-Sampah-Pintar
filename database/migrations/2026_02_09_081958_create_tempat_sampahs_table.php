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

        // IDENTITAS TEMPAT SAMPAH
        $table->string('kode_tempat')->unique(); // TS001
        $table->string('lokasi');

        // TINGGI TEMPAT SAMPAH
        $table->integer('kapasitas_cm');

        // DATA SENSOR ESP32
        $table->float('jarak_sensor')->nullable();
        $table->boolean('pir')->default(0);
        $table->boolean('relay_suara')->default(0);

        // HASIL PERHITUNGAN SISTEM
        $table->integer('persentase')->default(0);

        $table->enum('status', ['kosong', 'hampir_penuh', 'penuh'])
              ->default('kosong');

        // WAKTU UPDATE SENSOR
        $table->timestamp('last_sensor_update')->nullable();

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
