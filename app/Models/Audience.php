<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audience extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'birth_date',
        'phone',
        'email_verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function awardDraws(): HasMany
    {
        return $this->hasMany(AwardDraw::class);
    }

    public function hasVotedInCategory(int $categoryId): bool
    {
        return $this->votes()->where('category_id', $categoryId)->exists();
    }

    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->save();
    }
}
