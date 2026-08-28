<?php

use App\Http\Controllers\DownloadQuoteRequestAttachmentController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

$localizedPaths = [
    'nl' => [
        'maatwerk' => '/maatwerk',
        'werkwijze' => '/werkwijze',
        'inspiratie' => '/inspiratie',
        'prijzen' => '/prijzen',
        'about' => '/over-ons',
        'contact' => '/contact',
        'privacy' => '/privacy',
        'cookies' => '/cookies',
        'accessibility' => '/toegankelijkheid',
        'quote_requests.create' => '/offerte-aanvragen',
        'quote_requests.thank_you' => '/bedankt',
    ],
    'fr' => [
        'maatwerk' => '/sur-mesure',
        'werkwijze' => '/notre-methode',
        'inspiratie' => '/inspiration',
        'prijzen' => '/prix',
        'about' => '/a-propos',
        'contact' => '/contact',
        'privacy' => '/confidentialite',
        'cookies' => '/cookies',
        'accessibility' => '/accessibilite',
        'quote_requests.create' => '/configurateur',
        'quote_requests.thank_you' => '/merci',
    ],
];

$registerLocalizedRoutes = static function (string $locale, string $prefix, string $namePrefix) use ($localizedPaths): void {
    $paths = $localizedPaths[$locale];

    Route::prefix($prefix)
        ->name($namePrefix)
        ->middleware('locale:'.$locale)
        ->group(function () use ($paths): void {
            Route::middleware('cache.headers:public;max_age=3600;s_maxage=86400;stale_while_revalidate=604800;etag')->group(function () use ($paths): void {
                Route::view('/', 'home')->name('home');
                Route::view($paths['maatwerk'], 'pages.maatwerk')->name('maatwerk');
                Route::view($paths['werkwijze'], 'pages.werkwijze')->name('werkwijze');
                Route::view($paths['inspiratie'], 'pages.inspiratie')->name('inspiratie');
                Route::view($paths['prijzen'], 'pages.prijzen')->name('prijzen');
                Route::view($paths['about'], 'pages.about')->name('about');
                Route::view($paths['contact'], 'pages.contact')->name('contact');
                Route::view($paths['privacy'], 'pages.privacy')->name('privacy');
                Route::view($paths['cookies'], 'pages.cookies')->name('cookies');
                Route::view($paths['accessibility'], 'pages.accessibility')->name('accessibility');
            });

            Route::get($paths['quote_requests.create'], [QuoteRequestController::class, 'create'])
                ->name('quote_requests.create');
            Route::post($paths['quote_requests.create'], [QuoteRequestController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('quote_requests.store');
            Route::get($paths['quote_requests.thank_you'], [QuoteRequestController::class, 'thankYou'])
                ->name('quote_requests.thank_you');
        });
};

$registerLocalizedRoutes('nl', '', '');
$registerLocalizedRoutes('fr', 'fr', 'fr.');

Route::get('/aanvragen/{quoteRequest}/bijlagen/{attachment}', DownloadQuoteRequestAttachmentController::class)
    ->middleware(['signed:relative', 'throttle:30,1'])
    ->whereNumber('quoteRequest')
    ->whereNumber('attachment')
    ->name('quote_requests.attachments.download');
