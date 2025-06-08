<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['name'];

    public function headMajor() {
        return $this->hasOne(User::class);
    }

    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }
}
