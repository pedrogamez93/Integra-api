<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    protected $casts = [
        'content' => 'array',
    ];
}
