<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalExam extends Model
{
    protected $table = "vocational_exam";

    protected $fillable = ['name', 'period', 'description'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function vactionExamMajors() {
        return $this->hasManyThrough(
            Major::class,
            ExamDay::class,
            'vocational_exam_id', // FK di exam_days
            'id',                 // PK di majors
            'id',                 // PK di vocational_exam
            'major_id'            // FK exam_days → majors
        );
    }

    public function examDays() {
        return $this->hasMany(ExamDay::class);
    }
}
