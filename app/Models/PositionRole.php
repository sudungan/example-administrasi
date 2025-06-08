<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PositionRole extends Model
{
    protected $fillable = ['user_id', 'role_id', 'addition_role_id', 'start_periode', 'end_periode', 'status'];

}
