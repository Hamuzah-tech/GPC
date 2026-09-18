<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'sort_order',
    ];

    public function imageUrl(): string
    {
        return Media::url($this->image);
    }
}
