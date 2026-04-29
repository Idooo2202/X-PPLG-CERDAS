<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    protected $table = 'activity_log';
    protected $fillable = ['user_id', 'aksi', 'deskripsi'];
    public function user() { return $this->belongsTo(User::class); }
}