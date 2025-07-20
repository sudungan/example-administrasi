<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'teacher_id', 'major_id'];

    public function teacher() { // relasi untuk menjadikan wali kelas
        return $this->belongsTo(User::class);
    }

    public function major() {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function students() {
        return $this->hasMany(User::class);
    }
}
