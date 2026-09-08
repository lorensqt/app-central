<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectionVote extends Model
{
    protected $fillable = [
        'voter_id',
        'position_id',
        'candidate_id',
    ];

    /**
     * Get the voter who cast this vote.
     */
    public function voter(): BelongsTo
    {
        return $this->belongsTo(ElectionVoter::class, 'voter_id');
    }

    /**
     * Get the position.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(ElectionPosition::class, 'position_id');
    }

    /**
     * Get the candidate.
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(ElectionCandidate::class, 'candidate_id');
    }
}
