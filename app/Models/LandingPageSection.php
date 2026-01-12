<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageSection extends Model
{
    protected $fillable = [
        'key',
        'title',
        'content',
        'image',
        'video_url',
        'metadata',
        'order',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    public static function getByKey(string $key): ?self
    {
        return static::where('key', $key)->where('is_active', true)->first();
    }
}
