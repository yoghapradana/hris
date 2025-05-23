<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // using default user table "users"
    // uncomment below and if using different table name
    // protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

   protected $fillable = [
        'username',
        'email',
        'password',
        'user_level'
    ];

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


    //Define the relationship with UserProfile   
    public function profile()
    {
        return $this->hasOne(UserProfile::class, foreignKey: 'id');
    }

    public function isAdmin(): bool
    {
        return $this->user_level === 'admin';
    }

    public function userAttendance()
    {
        return $this->hasMany(UserAttendance::class, foreignKey: 'user_id');
    }

    public function userTimesheets()
    {
        return $this->hasMany(Timesheet::class, foreignKey: 'user_id');
    }
}
