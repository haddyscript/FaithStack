<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Donation $donation,
        public readonly Tenant   $tenant,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Thank you for your donation to {$this->tenant->name}!",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.donation-receipt');
    }
}
