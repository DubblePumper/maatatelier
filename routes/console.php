<?php

use App\Models\QuoteRequest;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('model:prune', ['--model' => [QuoteRequest::class]])
    ->dailyAt('02:30')
    ->onOneServer()
    ->withoutOverlapping();
