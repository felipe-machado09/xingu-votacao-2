<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'audience_id',
        'category_id',
        'company_id',
        'ip_hash',
        'user_agent',
        'created_at',
        'year',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'year' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($vote) {
            $vote->created_at = now();
            $vote->year ??= LandingPageSection::campaignYear();
        });
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('votes.year', $year);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
