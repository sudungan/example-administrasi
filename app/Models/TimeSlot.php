<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slot';

    protected $fillable = ['start_time', 'end_time'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }

}
