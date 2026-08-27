<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadQuoteRequestAttachmentController extends Controller
{
    public function __invoke(int $quoteRequest, int $attachment): StreamedResponse
    {
        $quoteRequest = QuoteRequest::query()->findOrFail($quoteRequest);
        $storedAttachment = ($quoteRequest->attachments ?? [])[$attachment] ?? null;
        $path = is_array($storedAttachment) ? ($storedAttachment['path'] ?? null) : null;

        abort_unless(
            is_string($path)
            && str_starts_with($path, 'quote-requests/')
            && Storage::disk('local')->exists($path),
            404,
        );

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $reference = str_pad((string) $quoteRequest->id, 5, '0', STR_PAD_LEFT);
        $downloadName = 'MAAT-'.$reference.'-bijlage-'.($attachment + 1).($extension ? '.'.$extension : '');

        return Storage::disk('local')->download($path, $downloadName, [
            'Cache-Control' => 'no-store, private',
            'Content-Type' => $storedAttachment['mime_type'] ?? 'application/octet-stream',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
