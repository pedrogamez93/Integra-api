<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRutList extends Model
{
    public function user()
    {
        return $this->belongsToMany(UserNova::class, 'survey_users', 'user_rut_list_id', 'user_id');
    }
}