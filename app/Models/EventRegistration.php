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
        'status',
        'attended',
        'attended_at',
        'ticket_code',
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'attended' => 'boolean',
    ];

    /**
     * Get the event associated with this registration.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
