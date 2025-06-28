<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['name', 'slug', 'user_id', 'initial'];

    public function headMajor() {
        return $this->belongsTo(User::class);
    }

    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }
}
