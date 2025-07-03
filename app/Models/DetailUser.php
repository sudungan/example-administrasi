<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $table = 'detail_user';
    protected $fillable = [
        'user_id',
        'password',
        'address',
        'first_name',
        'last_name',
        'phone_number',
        'major_id',
        'classroom_id',
        'place_of_birth',
        'date_of_birth'
    ];

    public function userDetail() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
}
