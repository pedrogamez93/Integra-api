<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Constribution extends Model
{

    protected $casts = [
        'init_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
