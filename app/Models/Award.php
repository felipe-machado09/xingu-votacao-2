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
    ];

    protected $casts = [
        'quantity' => 'integer',
        'tier' => 'integer',
        'min_votes' => 'integer',
        'is_active' => 'boolean',
    ];

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
}
