<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Onboarding extends Model implements HasMedia
{
    use HasMediaTrait;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232);

        $this->addMediaConversion('image_onboarding')
            ->width(640)
            ->height(610)
            ->performOnCollections('image_onboarding');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image_onboarding')->singleFile();
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }

}
