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
        'end_date',
        'location',
        'location_type',
        'arrival_instructions',
        'image',
        'max_participants',
        'registration_type',
        'registration_deadline',
        'registration_fields',
        'committee_id',
        'survey_enabled',
        'survey_questions',
        'survey_sent',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'registration_fields' => 'array',
        'survey_enabled' => 'boolean',
        'survey_questions' => 'array',
        'survey_sent' => 'boolean',
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

    /**
     * Get the formatted date range (Start - End) of the event.
     */
    public function getFormattedDateRangeAttribute(): string
    {
        $start = $this->event_date;
        $end = $this->end_date ?? $start;

        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
            // Same day: Monday, September 5, 2026 • 10:00 AM - 12:00 PM
            return $start->format('l, F j, Y • g:i A') . ' - ' . $end->format('g:i A');
        }

        // Different days: Monday, September 5, 2026, 10:00 AM - Tuesday, September 6, 2026, 12:00 PM
        return $start->format('l, F j, Y, g:i A') . ' - ' . $end->format('l, F j, Y, g:i A');
    }

    /**
     * Get the short formatted date range (Start - End) of the event.
     */
    public function getShortFormattedDateRangeAttribute(): string
    {
        $start = $this->event_date;
        $end = $this->end_date ?? $start;

        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
            return $start->format('l, M j • g:i A') . ' - ' . $end->format('g:i A');
        }

        return $start->format('M j, g:i A') . ' - ' . $end->format('M j, g:i A');
    }
}
