<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Termn extends Model
{
    protected $casts = [
        'name' => 'array'
    ];
}
