<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class QuoteRequestReceived extends Mailable
{
    public function __construct(public QuoteRequest $quoteRequest)
    {
        // Sent synchronously because the production hosting has no queue worker.
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->quoteRequest->email, $this->quoteRequest->name)],
            subject: 'Nieuwe maatwerkaanvraag #'.$this->quoteRequest->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.quote-requests.received',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
