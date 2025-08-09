<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slot';

    protected $fillable = ['activity', 'start_time', 'end_time', 'category'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

}
