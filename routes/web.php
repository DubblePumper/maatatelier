<?php

use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('cache.headers:public;max_age=3600;s_maxage=86400;stale_while_revalidate=604800;etag')->group(function (): void {
    Route::view('/', 'home')->name('home');
    Route::view('/maatwerk', 'pages.maatwerk')->name('maatwerk');
    Route::view('/werkwijze', 'pages.werkwijze')->name('werkwijze');
    Route::view('/inspiratie', 'pages.inspiratie')->name('inspiratie');
    Route::view('/prijzen', 'pages.prijzen')->name('prijzen');
    Route::view('/over-ons', 'pages.about')->name('about');
    Route::view('/contact', 'pages.contact')->name('contact');
    Route::view('/privacy', 'pages.privacy')->name('privacy');
    Route::view('/toegankelijkheid', 'pages.accessibility')->name('accessibility');
});

Route::get('/offerte-aanvragen', [QuoteRequestController::class, 'create'])
    ->name('quote_requests.create');
Route::post('/offerte-aanvragen', [QuoteRequestController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('quote_requests.store');
Route::get('/bedankt', [QuoteRequestController::class, 'thankYou'])
    ->name('quote_requests.thank_you');
