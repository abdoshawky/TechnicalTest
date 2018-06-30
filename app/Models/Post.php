<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'content'
    ];

    protected $casts = [
        'title'     => 'array',
        'content'   => 'array'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
