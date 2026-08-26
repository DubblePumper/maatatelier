<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SubmitIndexNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:submit-index-now
        {urls?* : Relatieve of canonieke URL’s; zonder argument worden alle publieke pagina’s verstuurd}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Meld gewijzigde publieke pagina’s bij IndexNow';

    public function handle(): int
    {
        $baseUrl = rtrim(config('maatatelier.canonical_url'), '/');
        $submittedUrls = $this->argument('urls') ?: config('maatatelier.indexable_paths');
        $urls = collect($submittedUrls)
            ->map(fn (string $url): string => str_starts_with($url, 'http') ? $url : $baseUrl.'/'.ltrim($url, '/'))
            ->unique()
            ->values();

        if ($urls->contains(fn (string $url): bool => parse_url($url, PHP_URL_HOST) !== parse_url($baseUrl, PHP_URL_HOST))) {
            $this->error('Alle URL’s moeten tot het canonieke MAATATELIER-domein behoren.');

            return self::FAILURE;
        }

        try {
            $response = Http::asJson()
                ->timeout(10)
                ->retry([250, 750], throw: false)
                ->post('https://api.indexnow.org/indexnow', [
                    'host' => parse_url($baseUrl, PHP_URL_HOST),
                    'key' => config('maatatelier.indexnow_key'),
                    'keyLocation' => $baseUrl.'/'.config('maatatelier.indexnow_key').'.txt',
                    'urlList' => $urls->all(),
                ]);
        } catch (ConnectionException $exception) {
            report($exception);
            $this->error('IndexNow kon niet worden bereikt. Probeer het later opnieuw.');

            return self::FAILURE;
        }

        if ($response->failed()) {
            $this->error("IndexNow weigerde de aanvraag met status {$response->status()}.");

            return self::FAILURE;
        }

        $this->info($urls->count().' publieke URL’s zijn bij IndexNow aangemeld.');

        return self::SUCCESS;
    }
}
