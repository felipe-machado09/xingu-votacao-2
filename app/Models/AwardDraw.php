<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwardDraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'award_id',
        'audience_id',
        'status',
        'drawn_at',
        'meta',
    ];

    protected $casts = [
        'drawn_at' => 'datetime',
        'meta' => 'array',
    ];

    public function award(): BelongsTo
    {
        return $this->belongsTo(Award::class);
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }
}
