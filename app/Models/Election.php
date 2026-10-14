<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Election extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status', // draft, active, closed
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Election $election) {
            if (empty($election->slug) && !empty($election->title)) {
                $election->slug = static::generateUniqueSlug($election->title);
            }
        });

        static::updating(function (Election $election) {
            if ($election->isDirty('title') && (empty($election->slug) || $election->isDirty('slug') === false)) {
                $election->slug = static::generateUniqueSlug($election->title, $election->id);
            }
        });
    }

    /**
     * Generate a unique slug for the election.
     */
    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title) ?: 'election';
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Route model binding by slug or ID fallback.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->first() ?? $this->where('slug', $value)->firstOrFail();
        }

        return $this->where('slug', $value)->first() ?? $this->where('id', $value)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the positions defined for this election.
     */
    public function positions(): HasMany
    {
        return $this->hasMany(ElectionPosition::class)->orderBy('sort_order');
    }

    /**
     * Get the candidates for this election through positions.
     */
    public function candidates(): HasManyThrough
    {
        return $this->hasManyThrough(ElectionCandidate::class, ElectionPosition::class, 'election_id', 'position_id');
    }

    /**
     * Get the voters registered/voted for this election.
     */
    public function voters(): HasMany
    {
        return $this->hasMany(ElectionVoter::class);
    }

    /**
     * Check if a specific user has voted in this election.
     */
    public function hasVoted(int $userId): bool
    {
        return $this->voters()
            ->where('user_id', $userId)
            ->whereNotNull('voted_at')
            ->exists();
    }
}
