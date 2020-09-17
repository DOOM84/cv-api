<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = ['ru_name', 'en_name', 'ua_name', 'year',
        'ru_description', 'ua_description', 'en_description', 'color'];

}
