<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSubject extends Model
{
    protected $table = 'schedules';

    protected $fillable = ['subject_id', 'classroom_id', 'day', 'schedules_time_id'];
}
