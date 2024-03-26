<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['id', 'object_id'];

    protected $casts = [
        'created_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function Post()
    {
        return $this->hasMany(Post::class, 'id', 'object_id');
    }

    public function Release()
    {
        return $this->hasMany(Release::class, 'id', 'object_id');
    }
}
