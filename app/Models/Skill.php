<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Skill extends Model
{
    use Sluggable;

    protected $fillable = ['ru_name', 'en_name', 'ua_name',
        'slug', 'rate', 'status', 'color'];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'ru_name'
            ]
        ];
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_skill');
    }
}
