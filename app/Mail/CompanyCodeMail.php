<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $companyName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu código de acesso - Melhores do Ano 2025',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company-code',
        );
    }
}
