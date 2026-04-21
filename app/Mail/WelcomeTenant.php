<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeTenant extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Tenant $tenant,
        public readonly Plan   $plan,
    ) {}

    public function envelope(): Envelope
    {
        $name = $this->tenant->name;
        return new Envelope(
            subject: "Welcome to FaithStack, {$name} — your site is live",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.welcome-tenant');
    }
}
