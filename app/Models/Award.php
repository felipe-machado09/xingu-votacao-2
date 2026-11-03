<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'quantity',
        'tier',
        'min_votes',
        'is_active',
        'year',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'tier' => 'integer',
        'min_votes' => 'integer',
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Award $award) {
            $award->year ??= LandingPageSection::campaignYear();
        });
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('year', LandingPageSection::campaignYear());
    }

    public function draws(): HasMany
    {
        return $this->hasMany(AwardDraw::class);
    }

    public function completedDraws(): HasMany
    {
        return $this->hasMany(AwardDraw::class)->where('status', 'completed');
    }

    public function remainingQuantity(): int
    {
        $completed = $this->completedDraws()->count();

        return max(0, $this->quantity - $completed);
    }

    public function hasRemainingQuantity(): bool
    {
        return $this->remainingQuantity() > 0;
    }

    public function requiredVotes(): int
    {
        return $this->tier === 3 ? Category::where('is_active', true)->count() : $this->min_votes;
    }

    public function qualifyingVotes(): \Illuminate\Database\Eloquent\Builder
    {
        return Vote::forYear($this->year)
            ->when($this->tier === 3, fn ($query) => $query
                ->whereHas('category', fn ($categories) => $categories->where('is_active', true)));
    }

    public function requirementLabel(): string
    {
        return $this->tier === 3 ? 'todas as categorias' : $this->requiredVotes().' categorias';
    }
}
