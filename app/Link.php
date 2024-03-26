<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
   /*  public function new()
    {
        return $this->belongsToMany(LinkPost::class, 'link_posts', 'post_id', 'link_id');
    }
    public function release()
    {
        return $this->belongsToMany(LinkRelease::class, 'link_news', 'link_id', 'post_id');
    }

    public function benefit()
    {
        return $this->belongsToMany(LinkBenefit::class, 'link_benefits', 'benefit_id', 'link_id');
    } */
}
