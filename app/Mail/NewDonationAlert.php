<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDonationAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Donation $donation,
        public readonly Tenant   $tenant,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New donation received — {$this->donation->full_name} gave \${$this->donation->amount}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.new-donation-alert');
    }
}
