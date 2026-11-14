<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /* ---------------------------------------------------------------------
     |  Dashboard query helpers (used by the Elections Dashboard index)
     | ------------------------------------------------------------------ */

    /**
     * Adds `positions_count` and `voters_count`.
     * `voters_count` only counts voters who actually cast a vote (voted_at set),
     * so the card's "Votes Cast" stays accurate even if voters are registered up front.
     */
    public function scopeWithDashboardCounts(Builder $query): Builder
    {
        return $query->withCount([
            'positions',
            'voters' => fn (Builder $q) => $q->whereNotNull('voted_at'),
        ]);
    }

    /**
     * Filter by status. Anything other than draft/active/closed (e.g. "all") is ignored.
     */
    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return in_array($status, ['draft', 'active', 'closed'], true)
            ? $query->where('status', $status)
            : $query;
    }

    /**
     * Search title and description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }

        // Escape LIKE wildcards so a search for "100%" or "_" behaves literally
        $like = '%' . addcslashes($term, '%_\\') . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('title', 'like', $like)
              ->orWhere('description', 'like', $like);
        });
    }

    /**
     * Sort options used by the dashboard dropdown: latest (default), ending, votes.
     * "votes" needs scopeWithDashboardCounts() applied first.
     */
    public function scopeSortBy(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'ending' => $query->orderByRaw('end_date IS NULL')->orderBy('end_date'),
            'votes'  => $query->orderByDesc('voters_count')->latest(),
            default  => $query->latest(),
        };
    }

    /**
     * Counts for the dashboard stats strip and filter pills, in a single query.
     * Always unfiltered, so the numbers don't change while a filter is applied.
     *
     * @return array{all:int, active:int, draft:int, closed:int}
     */
    public static function statusCounts(): array
    {
        $rows = static::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'all'    => (int) $rows->sum(),
            'active' => (int) ($rows['active'] ?? 0),
            'draft'  => (int) ($rows['draft'] ?? 0),
            'closed' => (int) ($rows['closed'] ?? 0),
        ];
    }
}