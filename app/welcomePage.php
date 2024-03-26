<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class welcomePage extends Model
{
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $attributes = [
        'is_public' => true,
    ];
}
