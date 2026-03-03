<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TvSession extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
        'activated_at',
        'expires_at',
        'activated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Admin que ativou a sessão
     */
    public function activatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    /**
     * Verifica se a sessão está ativa e não expirou
     */
    public function isAuthorized(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && now()->isAfter($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Gera um código único de 6 dígitos
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->where('is_active', false)->exists());

        return $code;
    }

    /**
     * Ativa a sessão da TV
     */
    public function activate(?int $userId = null, ?string $name = null): void
    {
        $this->update([
            'is_active' => true,
            'activated_at' => now(),
            'activated_by' => $userId,
            'name' => $name ?? $this->name,
        ]);
    }

    /**
     * Desativa a sessão da TV
     */
    public function deactivate(): void
    {
        $this->update([
            'is_active' => false,
        ]);
    }

    /**
     * Scope: sessões ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
