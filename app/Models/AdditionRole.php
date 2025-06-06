<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionRole extends Model
{
    protected $table = 'addtion_role';

    protected $fillable = ['name', 'role_id'];

    public function role() {
        return $this->belongsTo(Role::class);
    }
}
