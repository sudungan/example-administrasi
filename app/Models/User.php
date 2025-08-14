<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'password', 'email', 'role_id', 'classroom_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // role utama
    public function role() {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    // user memiliki banyak role tambahan
    public function additionRoles() {
        return $this->belongsToMany(AdditionRole::class, 'addition_role_user', 'user_id', 'addition_role_id')->withTimestamps();
    }

    public function detail() {
        return $this->hasOne(detailUser::class);
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class, 'classroom_id', 'id');
    }

    public function subjects() {
        return $this->hasMany(Subject::class);
    }

    public function amountSubjects() {
        return $this->hasOne(SubjectTeacher::class);
    }

    public function subjectJp() {
        return $this->hasOne(SubjectTeacher::class, 'user_id', 'id');
    }

    public function subjectColour() {
        return $this->hasOne(TeacherColour::class);
    }
}
