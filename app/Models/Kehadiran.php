<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model {
    protected $table = 'kehadiran';
    protected $fillable = ['user_id', 'tanggal', 'status', 'keterangan', 'dicatat_oleh'];
    protected $casts = ['tanggal' => 'date'];
    public function user() { return $this->belongsTo(User::class); }
    public function pencatat() { return $this->belongsTo(User::class, 'dicatat_oleh'); }
}