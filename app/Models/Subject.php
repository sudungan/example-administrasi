<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['user_id', 'classroom_id', 'name', 'total_jp', 'colour'];

    public function classroom() {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
