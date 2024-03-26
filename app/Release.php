<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Release extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250)
            ->nonQueued();

        $this->addMediaConversion('image_release')
            ->width(640)
            ->height(370)
            ->performOnCollections('image_release');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image_release');
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }

    public function linkRelease()
    {
        return $this->belongsToMany(Link::class, 'link_releases', 'release_id', 'link_id');
    }

    public function district()
    {
        return $this->belongsToMany(District::class, 'release_districts', 'release_id', 'district_id');
    }

    public function region()
    {
        return $this->belongsToMany(Region::class, 'release_regions', 'release_id', 'region_id');
    }

    public function position()
    {
        return $this->belongsToMany(Position::class, 'release_positions', 'release_id', 'position_id');
    }

    public function benefit()
    {
        return $this->belongsToMany(Benefit::class, 'release_benefits', 'release_id', 'benefits_id');
    }
    public function benefitPost()
    {
        return $this->belongsToMany(Benefit::class, 'release_benefits', 'release_id', 'benefits_id');
    }
}

