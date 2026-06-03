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
        Schema::create('tugas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_tugas');
            $table->text('deskripsi');
            $table->datetime('tanggal_tugas');
            $table->datetime('deadline_tugas');
            $table->string('prioritas', 200);
            $table->string('status_tugas', 50)->default('Belum Selesai');
            $table->string('kategoritugas', 50); //kelompok atau individu
            $table->foreignUlid('departemen_id')->constrained('departemens')->cascadeOnDelete();
            $table->text('catatan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
