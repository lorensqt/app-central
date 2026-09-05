<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'terms_and_policy',
        'event_date',
        'location',
        'location_type',
        'arrival_instructions',
        'image',
        'max_participants',
        'registration_type',
        'registration_deadline',
        'registration_fields',
        'committee_id',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'registration_fields' => 'array',
    ];

    /**
     * Get the committee associated with this event.
     */
    public function committee(): BelongsTo
    {
        return $this->belongsTo(Committee::class);
    }

    /**
     * Get the registrations for this event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
}
