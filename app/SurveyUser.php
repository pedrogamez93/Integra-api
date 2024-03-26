<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyUser extends Model
{
    protected $fillable = [
        'user_id',
        'user_rut_list_id',
    ];
}