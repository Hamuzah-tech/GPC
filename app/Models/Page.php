<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'body',
        'extra',
    ];

    public static function bySlug(string $slug): ?self
    {
        return static::query()->where('slug', $slug)->first();
    }
}
