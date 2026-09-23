<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'allow_group_registration',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'registration_fields' => 'array',
        'survey_enabled' => 'boolean',
        'survey_questions' => 'array',
        'survey_sent' => 'boolean',
        'allow_group_registration' => 'boolean',
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

    /**
     * Check if the event has ended.
     */
    public function isEnded(): bool
    {
        $end = $this->end_date ?? $this->event_date->copy()->addHours(2);
        return now()->isAfter($end);
    }

    /**
     * Check if the event is currently ongoing.
     */
    public function isOngoing(): bool
    {
        $start = $this->event_date;
        $end = $this->end_date ?? $this->event_date->copy()->addHours(2);
        return now()->between($start, $end);
    }

    /**
     * Check if the event is upcoming (has not started yet).
     */
    public function isUpcoming(): bool
    {
        return now()->isBefore($this->event_date);
    }

    /**
     * Check if the event starts today.
     */
    public function startsToday(): bool
    {
        return $this->event_date->isToday();
    }

    /**
     * Check if the event starts tomorrow.
     */
    public function startsTomorrow(): bool
    {
        return $this->event_date->isTomorrow();
    }

    /**
     * Get the number of days until the event starts.
     */
    public function daysUntilStart(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->event_date->startOfDay(), false);
    }

    /**
     * Check if self check-in is allowed for this event.
     * Check-in is typically allowed on the day of the event until the event ends.
     */
    public function canCheckIn(): bool
    {
        if ($this->isEnded()) {
            return false;
        }

        // Allowed if it's ongoing or starting today (on the same calendar day)
        return $this->isOngoing() || $this->event_date->isToday();
    }

    /**
     * Get the SEO-friendly public URL for the event landing page.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('events.public_show', [
            'event' => $this->id,
            'slug' => Str::slug($this->title),
        ]);
    }
}
