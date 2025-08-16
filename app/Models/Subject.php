<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = ['name', 'user_id', 'colour'];

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function classroomSubject() {
        return $this->belongsToMany(Classroom::class, 'classroom_subject')->withPivot('jumlah_jp')->withTimestamps();;
    }

    public function teacherJp() {
       return $this->hasOneThrough(
            SubjectTeacher::class, // model akhir
            User::class,           // model perantara
            'id',                  // FK di tabel User → user.id
            'user_id',             // FK di SubjectTeacher → subject_teacher.user_id
            'user_id',             // FK di Subject → subject.user_id
            'id'                   // PK di User
        );
    }

    public function scheduleSubjects() {
        return $this->hasMany(Schedule::class);
    }

}
