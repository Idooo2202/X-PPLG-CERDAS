<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model {
    protected $fillable = ['jenis', 'jumlah', 'keterangan', 'tanggal', 'user_id'];
    protected $casts = ['tanggal' => 'date', 'jumlah' => 'decimal:2'];
    public function user() { return $this->belongsTo(User::class); }
}