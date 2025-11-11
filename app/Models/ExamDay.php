<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamDay extends Model
{
    protected $table = 'exam_days';

    protected $fillable = [
        'exam_index', // untuk menjelaskan ujian hari ke 1 atau 2
        'day', // untuk menjelaskan tanggal ujian yang dilaksanakan
        'major_id',  // untuk menjelaskan jurusan
        'vocational_exam_id' // untuk foreign key
    ];

    public function vocationalExam() {
        return $this->belongsTo(VocationalExam::class, 'vocational_exam_id', 'id');
    }

    public function major() {
        return $this->belongsTo(Major::class);
    }
}
