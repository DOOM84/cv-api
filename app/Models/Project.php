<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = ['ru_name', 'en_name', 'ua_name',
        'ru_details', 'en_details', 'ua_details', 'image', 'status'];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
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
            ->url('uploads/Projects/'.$size.'/'.$this->image);
    }
}
