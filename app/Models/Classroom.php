<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'teacher_id'];

    public function teacher() { // relasi untuk menjadikan wali kelas
        return $this->belongsTo(User::class, 'id');
    }
}
