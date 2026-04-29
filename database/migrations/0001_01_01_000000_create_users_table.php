<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();      // ← tambah ini
            $table->string('password');
            $table->string('nama_lengkap', 100);           // ← tambah ini
            $table->enum('role', [
                'wali_kelas',
                'bendahara',
                'sekretaris',
                'siswa'
            ])->default('siswa');                          // ← tambah ini
            $table->string('no_absen', 5)->nullable();     // ← tambah ini
            $table->string('foto_profil')->nullable();     // ← tambah ini
            $table->string('email', 100)->nullable();      // jadikan nullable
            $table->string('no_hp', 20)->nullable();       // ← tambah ini
            $table->boolean('is_active')->default(true);   // ← tambah ini
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};