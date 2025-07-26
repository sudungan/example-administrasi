<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherColour extends Model
{
    protected $table = 'teachers_colours';

    protected $fillable = ['user_id', 'colour'];

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function subject() {
    //     return $this->belongsTo(Subject::class, 'subject_id', 'id');
    // }
}
