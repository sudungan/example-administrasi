<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionRole extends Model
{
    protected $table = 'addtion_role';

    protected $fillable = ['name', 'slug', 'role_id'];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function userAdditionRoles() {
        return $this->belongsToMany(User::class, 'addition_role_user', 'addition_role_id', 'user_id');
    }
}
