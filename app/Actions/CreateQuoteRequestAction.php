<?php

namespace App\Actions;

use App\Models\QuoteRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateQuoteRequestAction
{
    /**
     * @param  array<string, mixed>  $validated
     */
    public function handle(array $validated): QuoteRequest
    {
        $storedAttachments = $this->storeAttachments(Arr::pull($validated, 'attachments', []));

        Arr::forget($validated, ['consent', 'website']);
        $validated['features'] = array_values($validated['features'] ?? []);
        $validated['attachments'] = $storedAttachments ?: null;
        $validated['consent_at'] = now();

        try {
            return QuoteRequest::create($validated);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete(collect($storedAttachments)->pluck('path')->all());

            throw $exception;
        }
    }

    /**
     * @param  list<UploadedFile>  $attachments
     * @return list<array{path: string, mime_type: string, size: int}>
     */
    private function storeAttachments(array $attachments): array
    {
        return collect($attachments)
            ->map(function (UploadedFile $attachment): array {
                $path = $attachment->store('quote-requests', 'local');

                return [
                    'path' => $path,
                    'mime_type' => $attachment->getMimeType() ?: 'application/octet-stream',
                    'size' => (int) $attachment->getSize(),
                ];
            })
            ->all();
    }
}
