<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_grups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('grup_kerja_id')->constrained('grup_kerjas')->cascadeOnDelete();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['grup_kerja_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_grups');
    }
};
