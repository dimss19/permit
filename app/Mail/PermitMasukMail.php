<?php

namespace App\Mail;

use App\Models\Permit;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermitMasukMail extends Mailable
{
    use Queueable, SerializesModels;

    public Permit $permit;

    /** @var 'staff'|'manager' */
    public string $targetRole;

    public string $reviewUrl;

    public function __construct(Permit $permit, string $targetRole = 'staff')
    {
        $this->permit = $permit->loadMissing(['user', 'classifications']);
        $this->targetRole = $targetRole;
        $this->reviewUrl = url('/admin/approvals/' . $permit->id);
    }

    public function envelope(): Envelope
    {
        $roleLabel = $this->targetRole === 'manager' ? 'Manager HSE' : 'Staff HSE';

        return new Envelope(
            subject: "[Permit Masuk - {$roleLabel}] {$this->permit->no_permit} menunggu review",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.permit-masuk',
            with: [
                'permit' => $this->permit,
                'targetRole' => $this->targetRole,
                'reviewUrl' => $this->reviewUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
