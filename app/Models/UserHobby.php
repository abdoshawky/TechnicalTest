<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHobby extends Model
{

    protected $table = 'user_hobby';

    protected $fillable = [
        'user_id',
        'hobby_id'
    ];
}
