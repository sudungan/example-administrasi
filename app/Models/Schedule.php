<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = ['name', 'classroom_id', 'subject_id', 'time_slot_id', 'type', 'day'];

    public function classrooms() {
        return $this->belongsToMany(Classroom::class);
    }

    public function subjects() {
        return $this->belongsToMany(Subject::class);
    }

    public function timeSlots() {
        return $this->belongsToMany(TimeSlot::class);
    }
}
