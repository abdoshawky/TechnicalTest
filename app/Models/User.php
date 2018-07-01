<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
        'verified',
        'image',
        'gender',
        'phone',
        'address',
        'delete_reason'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token'
    ];

    public function getImageAttribute(){
        return url('images/normal/'.$this->attributes['image']);
    }

    public function getImagePathAttribute($value){
        return 'uploaded/users/'.$this->attributes['image'];
    }

    public function getImageNameAttribute($value){
        return $this->attributes['image'];
    }

    public function hobbies(){
        return $this->belongsToMany(Hobby::class, 'user_hobby');
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
