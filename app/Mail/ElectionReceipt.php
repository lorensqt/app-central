<?php

namespace App\Mail;

use App\Models\Election;
use App\Models\ElectionVoter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ElectionReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public Election $election;
    public ElectionVoter $voter;
    public array $selections;

    /**
     * Create a new message instance.
     */
    public function __construct(Election $election, ElectionVoter $voter, array $selections = [])
    {
        $this->election = $election;
        $this->voter = $voter;
        $this->selections = $selections;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Official Election Voting Receipt: ' . $this->election->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.election_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
