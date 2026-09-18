<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Leader extends Model
{
    protected $fillable = [
        'name',
        'position',
        'bio_preview',
        'full_bio',
        'image',
        'sort_order',
    ];

    public function imageUrl(): string
    {
        return Media::url($this->image, '/images/logo.png');
    }
}
