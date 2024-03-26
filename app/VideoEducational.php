<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoEducational extends Model
{
    protected $casts = [
        'datetime' => 'datetime',
    ];
}
