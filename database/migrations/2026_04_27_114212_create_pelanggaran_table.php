<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siswa yg melanggar
            $table->foreignId('dilaporkan_oleh')->constrained('users')->onDelete('cascade'); // sekretaris
            $table->string('jenis_pelanggaran');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->enum('status', ['pending', 'ditangani', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pelanggaran'); }
};