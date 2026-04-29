<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 12, 2);
            $table->string('keterangan');
            $table->date('tanggal');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siapa yang input
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kas'); }
};