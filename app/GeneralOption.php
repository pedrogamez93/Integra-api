<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralOption extends Model
{
    protected $casts = [
        'datetime' => 'datetime',
    ];
}
