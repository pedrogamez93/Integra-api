<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SegmentedNotification extends Model
{
    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function Region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'code');
    }

    public function Dependence()
    {
        return $this->belongsTo(District::class, 'dependence_code', 'code');
    }

    public function Position()
    {
        return $this->belongsTo(District::class, 'position_code', 'code');
    }
}
