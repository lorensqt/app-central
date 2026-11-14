<?php

namespace App\Mail;

use App\Models\Election;
use App\Models\ElectionVoter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VoterLoginOtp extends Mailable
{
    use Queueable, SerializesModels;

    public Election $election;
    public ElectionVoter $voter;
    public string $otp;

    /**
     * Create a new message instance.
     */
    public function __construct(Election $election, ElectionVoter $voter, string $otp)
    {
        $this->election = $election;
        $this->voter = $voter;
        $this->otp = $otp;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Election Verification Code: {$this->otp} - {$this->election->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.voter_login_otp',
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
