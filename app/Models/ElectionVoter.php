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
        'name',
        'email',
        'division',
        'gender',
        'age',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    protected $appends = [
        'display_name',
    ];

    /**
     * Get the display name for the voter.
     */
    public function getDisplayNameAttribute(): string
    {
        if (!empty($this->name)) {
            return $this->name;
        }

        if ($this->user && !empty($this->user->name)) {
            return $this->user->name;
        }

        if (!empty($this->email)) {
            $parts = explode('@', $this->email);
            return ucwords(str_replace(['.', '_', '-'], ' ', $parts[0]));
        }

        return 'Anonymous Voter';
    }

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
