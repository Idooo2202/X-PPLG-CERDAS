<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class KasPayment extends Model {
    protected $fillable = ['user_id', 'tanggal_bayar', 'jumlah', 'is_paid', 'verified_by'];
    protected $casts = ['tanggal_bayar' => 'date', 'is_paid' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
}