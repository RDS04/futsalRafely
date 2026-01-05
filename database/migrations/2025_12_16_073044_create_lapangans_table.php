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
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->string('namaLapangan');
            $table->string('jenisLapangan');
            $table->integer('harga');
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->enum('region', ['padang', 'bukittinggi', 'sijunjung'])->default('padang');
            $table->text('deskripsi');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};
