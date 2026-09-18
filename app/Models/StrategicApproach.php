<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicApproach extends Model
{
    protected $fillable = [
        'title',
        'description',
        'sort_order',
    ];
}
