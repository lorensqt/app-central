<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ElectionPosition extends Model
{
    protected $fillable = [
        'election_id',
        'name',
        'max_votes',
        'sort_order',
    ];

    protected $casts = [
        'max_votes' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the election this position belongs to.
     */
    public function election(): BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    /**
     * Get the candidates for this position.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(ElectionCandidate::class, 'position_id')->orderBy('sort_order');
    }

    /**
     * Get the votes cast for this position.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ElectionVote::class, 'position_id');
    }
}
