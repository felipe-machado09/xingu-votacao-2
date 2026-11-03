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
        'document_path',
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

    public static function logoUrl(): string
    {
        $image = static::getByKey('campaign')?->image ?? static::getByKey('logo')?->image ?? static::getByKey('hero')?->image;

        return $image ? asset('storage/'.$image) : asset('img/logo.webp');
    }

    public static function votingEndsAt(): ?\Illuminate\Support\Carbon
    {
        $campaign = static::getByKey('campaign');
        $date = $campaign ? ($campaign->metadata['end_date'] ?? null) : (static::getByKey('countdown')?->metadata['end_date'] ?? null);

        return $date ? \Illuminate\Support\Carbon::parse($date) : null;
    }

    public static function regulationUrl(): ?string
    {
        $path = static::getByKey('campaign')?->document_path ?? static::getByKey('regulation')?->document_path;

        return $path ? asset('storage/'.$path) : null;
    }

    public static function campaignYear(): int
    {
        return (int) (static::getByKey('campaign')?->metadata['year'] ?? now()->year);
    }

    public static function campaignOption(string $key, bool $default = true): bool
    {
        return (bool) (static::getByKey('campaign')?->metadata[$key] ?? $default);
    }
}
