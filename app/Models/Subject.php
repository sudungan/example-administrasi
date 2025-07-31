<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name', 'user_id', 'classroom_id', 'jumlah_jp', 'colour'];

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
