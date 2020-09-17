<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Sluggable;

    protected $fillable = ['ru_name', 'en_name', 'ua_name', 'slug', 'status'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'ru_name'
            ]
        ];
    }
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'category_post');
    }
}
