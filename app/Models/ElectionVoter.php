<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionVoter extends Model
{
    protected $fillable = [
        'election_id',
        'user_id',
        'email',
        'division',
        'current_position',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Get the election.
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the votes cast by this voter.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class, 'voter_id');
    }
}
