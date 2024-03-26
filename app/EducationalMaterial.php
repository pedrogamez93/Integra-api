<?php

namespace App;

use App\Category;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class EducationalMaterial extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(250)
            ->height(250)
            ->nonQueued();

        $this->addMediaConversion('image_educational_material')
            ->width(640)
            ->height(370)
            ->performOnCollections('image_educational_material');
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('image_educational_material');
    }

    public function mediaRelation()
    {
        return $this->hasMany(Media::class, 'model_id');
    }
}
