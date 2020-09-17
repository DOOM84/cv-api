<?php

namespace App\Models;

use App\Models\Traits\FullTextSearch;
use App\Models\Traits\Likeable;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Date\Date;

class Post extends Model
{
    use Sluggable, Taggable, Likeable, FullTextSearch;

    protected $fillable = ['ru_title', 'en_title', 'ua_title', 'ru_subtitle', 'en_subtitle', 'ua_subtitle',
        'slug', 'body', 'source', 'author', 'image', 'status'];

    protected $searchable = ['ru_title', 'ua_title', 'en_title', 'ru_subtitle',
        'en_subtitle', 'ua_subtitle', 'body'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'ru_title'
            ]
        ];
    }

    public function getDateAttribute()
    {
        $date = explode('-', Date::parse($this->created_at)->format('j-F-Y'));
        $date[1] = mb_substr($date[1], 0, 3);
        return $date;
    }

    public function getImagesAttribute()
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'original' => $this->getImagePath('original'),
        ];
    }

    protected function getImagePath($size)
    {
        return Storage::disk('public')
            ->url('uploads/Posts/'.$size.'/'.$this->image);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_post');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->orderBy('created_at', 'asc');

    }
}
