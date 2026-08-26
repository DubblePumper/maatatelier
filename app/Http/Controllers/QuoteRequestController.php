<?php

namespace App\Http\Controllers;

use App\Actions\CreateQuoteRequestAction;
use App\Http\Requests\StoreQuoteRequest;
use App\Mail\QuoteRequestConfirmation;
use App\Mail\QuoteRequestReceived;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(): View
    {
        return view('quote-requests.create');
    }

    public function store(StoreQuoteRequest $request, CreateQuoteRequestAction $createQuoteRequest): RedirectResponse
    {
        $quoteRequest = $createQuoteRequest->handle($request->validated());

        Mail::to($quoteRequest->email)->send(new QuoteRequestConfirmation($quoteRequest));

        if (config('maatatelier.quote_recipient')) {
            Mail::to(config('maatatelier.quote_recipient'))->send(new QuoteRequestReceived($quoteRequest));
        }

        return redirect()
            ->route('quote_requests.thank_you')
            ->with('quote_request_number', $quoteRequest->id);
    }

    public function thankYou(): View
    {
        $quoteRequestNumber = session()->pull('quote_request_number');

        return view('quote-requests.thank-you', [
            'reference' => $quoteRequestNumber
                ? 'MAAT-'.str_pad((string) $quoteRequestNumber, 5, '0', STR_PAD_LEFT)
                : null,
        ]);
    }
}
