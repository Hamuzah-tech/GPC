<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    public const BECOMING_HER_SLUG = 'becoming-her';

    protected $fillable = [
        'name',
        'slug',
        'headline',
        'tagline',
        'session_label',
        'code',
        'year',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public static function becomingHer(): self
    {
        return static::query()
            ->where('slug', self::BECOMING_HER_SLUG)
            ->where('is_active', true)
            ->firstOrFail();
    }
}
