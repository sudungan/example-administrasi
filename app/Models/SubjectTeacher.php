<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// CLASS YANG MENGHITUNG JUMLAH JP DARI SELURUH MAPEL GURU
class SubjectTeacher extends Model
{
    protected $table = 'subjects_teacher';

    protected $fillable = ['user_id', 'total_jp'];

    public function teacher() {
        return $this->belongsTo(User::class);
    }
}
