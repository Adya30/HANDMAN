<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grup_kerjas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('nama_grup', 200);
            $table->text('deskripsi')->nullable();
            $table->foreignUlid('departemen_id')->constrained('departemens')->cascadeOnDelete();
            $table->foreignUlid('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grup_kerjas');
    }
};
