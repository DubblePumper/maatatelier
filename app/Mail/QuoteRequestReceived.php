<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\URL;

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
            with: [
                'attachmentLinks' => $this->attachmentLinks(),
            ],
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

    /**
     * @return list<array{url: string, name: string, size: int}>
     */
    private function attachmentLinks(): array
    {
        return collect($this->quoteRequest->attachments ?? [])
            ->map(function (array $attachment, int $index): array {
                return [
                    'url' => rtrim(config('maatatelier.canonical_url'), '/').URL::temporarySignedRoute(
                        'quote_requests.attachments.download',
                        now()->addDays(config('maatatelier.attachment_link_lifetime_days')),
                        ['quoteRequest' => $this->quoteRequest, 'attachment' => $index],
                        false,
                    ),
                    'name' => $attachment['original_name'] ?? 'Bijlage '.($index + 1),
                    'size' => (int) ($attachment['size'] ?? 0),
                ];
            })
            ->values()
            ->all();
    }
}
