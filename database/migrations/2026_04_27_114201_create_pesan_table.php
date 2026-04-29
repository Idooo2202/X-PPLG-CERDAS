<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dari_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ke_user_id')->nullable()->constrained('users')->nullOnDelete(); // null = broadcast ke semua
            $table->string('judul');
            $table->text('isi');
            $table->boolean('is_broadcast')->default(false);
            $table->boolean('is_read')->default(false);
            $table->foreignId('reply_to')->nullable()->constrained('pesan')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pesan'); }
};