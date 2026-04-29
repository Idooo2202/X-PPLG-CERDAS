<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';
    protected $fillable = [
        'user_id', 'dilaporkan_oleh', 'jenis_pelanggaran', 'deskripsi', 'tanggal', 'status'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function pelapor() { return $this->belongsTo(User::class, 'dilaporkan_oleh'); }
}
