<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Election extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status', // draft, active, closed
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

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
