<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'gender',
        'birthday',
        'division',
        'status',
        'rejection_reason',
        'group_code',
        'is_group_primary',
        'attended',
        'attended_at',
        'ticket_code',
        'custom_fields',
        'survey_responses',
    ];

    protected $casts = [
        'birthday' => 'date',
        'attended_at' => 'datetime',
        'attended' => 'boolean',
        'is_group_primary' => 'boolean',
        'custom_fields' => 'array',
        'survey_responses' => 'array',
    ];

    /**
     * Get the computed age of the registrant based on birthday.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birthday ? \Carbon\Carbon::parse($this->birthday)->age : null;
    }

    /**
     * Get the event associated with this registration.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
