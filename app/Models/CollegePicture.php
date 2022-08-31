<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegePicture extends Model
{

    protected $table = 'college_pictures';

    protected $fillable = [
        'college_id', 'name', 'url',
    ];

    protected $dates = [
        'created_at', 'updated_at',
    ];
}
