<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    //using default user profile table "user_profiles"
    //uncoment below if using diffrent table name
    //protected $table = 'user_profiles';

    protected $fillable = [        
        'id',
        'fullname',
        'img_pic_path',
        'division',
        'position',
        'entrydate',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, foreignKey: 'id');
    }
}
