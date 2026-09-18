<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'path',
        'alt',
        'type',
        'sort_order',
    ];

    public function url(): string
    {
        return Media::url($this->path);
    }
}
