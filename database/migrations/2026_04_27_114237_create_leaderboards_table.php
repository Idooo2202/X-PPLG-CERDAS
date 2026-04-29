<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Ganti 'leaderboard' → 'leaderboards'
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('poin')->default(0);
            $table->integer('streak_hadir')->default(0);
            $table->integer('streak_kas')->default(0);
            $table->integer('total_hadir')->default(0);
            $table->integer('total_kas_bayar')->default(0);
            $table->enum('tier', ['sultan', 'kaya', 'normal', 'kelas_bawah'])->default('kelas_bawah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboards'); // ✅ Ganti ini juga
    }
};