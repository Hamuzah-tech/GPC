<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'age',
        'gender',
        'location',
        'skills',
        'interests',
        'availability',
        'message',
        'cv_path',
        'cv_original_name',
    ];

    protected function casts(): array
    {
        return [
            'interests' => 'array',
            'age' => 'integer',
        ];
    }
}
