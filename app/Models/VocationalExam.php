<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalExam extends Model
{
    protected $table = "vocational_exam";

    protected $fillable = ['major_id', 'user_id', 'title', 'created_by'];

    // fungsi untuk relasi ke table majors
    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
