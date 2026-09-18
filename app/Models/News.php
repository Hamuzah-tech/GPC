<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class)->orderBy('sort_order');
    }

    public function coverImage(): ?NewsImage
    {
        return $this->images->firstWhere('is_cover', true) ?? $this->images->first();
    }

    public function coverUrl(): string
    {
        $cover = $this->coverImage();

        return $cover ? Media::url($cover->path) : Media::url('images/image19.jpg');
    }

    public function excerpt(int $length = 120): string
    {
        $text = trim(strip_tags((string) $this->content));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length).'...';
    }
}
