<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model {
    protected $table = 'pesan';
    protected $fillable = [
        'dari_user_id', 'ke_user_id', 'judul', 'isi',
        'is_broadcast', 'is_read', 'reply_to'
    ];
    public function pengirim() { return $this->belongsTo(User::class, 'dari_user_id'); }
    public function penerima() { return $this->belongsTo(User::class, 'ke_user_id'); }
    public function balasan() { return $this->hasMany(Pesan::class, 'reply_to'); }
}