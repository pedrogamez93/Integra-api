<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $casts = [
        'date' => 'datetime',
        'end_date' => 'datetime'
    ];
    public function district()
    {
        return $this->belongsToMany(District::class, 'survey_districts', 'survey_id', 'district_id');
    }

    public function region()
    {
        return $this->belongsToMany(Region::class, 'survey_regions', 'survey_id', 'region_id');
    }

    public function position()
    {
        return $this->belongsToMany(Position::class, 'survey_positions', 'survey_id', 'position_id');
    }

    public function user()
    {
        return $this->belongsToMany(UserNova::class, 'survey_users', 'survey_id', 'user_id');
    }

    public function userRutList()
    {
        return $this->belongsTo(UserRutList::class);
    }
}