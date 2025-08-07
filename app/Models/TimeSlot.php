<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slot';

    protected $fillable = ['activity', 'start_time', 'end_time', 'category'];

    protected const WEEK_DAYS = [
        // ['id'=> 1, 'key'    => 'senin', 'value'=> 'SENIN'],
        // ['id'=> 2, 'key'    => 'selasa', 'value'=> 'SELASA'],
        // ['id'=> 3, 'key'    => 'rabu', 'value'=> 'RABU'],
        // ['id'=> 4, 'key'    => 'kamis', 'value'=> 'KAMIS'],
        // ['id'=> 5, 'key'    => 'jumat', 'value'=> 'SENIN'],
        'senin'     => 'SENIN',
        'selasa'    =>  'SELASA',
        'rabu'      => 'RABU' ,
        'kamis'     => 'KAMIS',
        'jumat'     =>  'JUMAT'
    ];

    public static function getWeekDays() {
        return self::WEEK_DAYS;
    }
}
