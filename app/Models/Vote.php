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
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($vote) {
            $vote->created_at = now();
        });
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
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
