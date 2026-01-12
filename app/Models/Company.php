<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'legal_name',
        'slug',
        'email',
        'password',
        'cnpj',
        'responsible_name',
        'responsible_phone',
        'telefone',
        'whatsapp_number',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_zipcode',
        'lgpd_accepted',
        'role_name',
        'registration_complete',
        'logo_path',
        'main_image_path',
        'facade_image_path',
        'team_image_path',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'lgpd_accepted' => 'boolean',
        'registration_complete' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->legal_name);
            }
        });

        static::updating(function ($company) {
            if ($company->isDirty('legal_name') && empty($company->slug)) {
                $company->slug = Str::slug($company->legal_name);
            }
            
            if ($company->isDirty('password') && !empty($company->password)) {
                $company->password = Hash::make($company->password);
            }
        });

        static::creating(function ($company) {
            if (!empty($company->password) && !Hash::needsRehash($company->password)) {
                $company->password = Hash::make($company->password);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function categoryWinners(): HasMany
    {
        return $this->hasMany(CategoryWinner::class);
    }
}
