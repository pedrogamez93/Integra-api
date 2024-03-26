<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserContribution extends Model
{

    public function constribution()
    {
        return $this->belongsTo(Constribution::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(DB::raw('CONCAT(name, ", ", surname) AS name'), 'id');
    }
}
