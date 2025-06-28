<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function additionRole() {
        return $this->hasMany(AdditionRole::class);
    }
}
