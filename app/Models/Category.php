<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_group',
        'description',
        'is_active',
        'voting_starts_at',
        'voting_ends_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'voting_starts_at' => 'datetime',
        'voting_ends_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function winner(): HasOne
    {
        return $this->hasOne(CategoryWinner::class);
    }

    public function isOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->voting_starts_at && $now->isBefore($this->voting_starts_at)) {
            return false;
        }

        if ($this->voting_ends_at && $now->isAfter($this->voting_ends_at)) {
            return false;
        }

        return true;
    }

    public function scopeOpen($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('voting_starts_at')
                    ->orWhere('voting_starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('voting_ends_at')
                    ->orWhere('voting_ends_at', '>=', now());
            });
    }
}
