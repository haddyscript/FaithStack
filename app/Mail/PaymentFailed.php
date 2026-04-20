<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Tenant $tenant,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment failed — please update your billing details",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.payment-failed');
    }
}
