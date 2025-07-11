<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    protected $table = 'detail_user';
    protected $fillable = [
        'user_id',
        'address',
        'first_name',
        'last_name',
        'phone_number',
        'place_of_birth',
        'date_of_birth'
    ];

    public function userDetail() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
