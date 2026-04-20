<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiring extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Tenant $tenant,
        public readonly int    $daysRemaining,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your FaithStack subscription expires in {$this->daysRemaining} day(s) — action required",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.subscription-expiring');
    }
}
