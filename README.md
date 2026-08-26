# MAATATELIER

Publieke Laravel-website voor maatatelier.be, met informatie over maatkasten, keukens en interieur en een toegankelijke offerteflow met privé opgeslagen bijlagen.

## Lokaal starten

Vereisten: PHP 8.5, Composer 2, Node.js en npm.

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
composer run dev
```

Op macOS of Linux gebruik je `cp .env.example .env` in plaats van `copy`.

## Productieconfiguratie

Nieuwe aanvragen gaan standaard naar `interieuratelieropmaat@gmail.com`; dit kan via `MAATATELIER_QUOTE_RECIPIENT` worden overschreven. Configureer een echte maildriver, laat een queue worker draaien voor de bevestigingsmails en voer Laravel's scheduler iedere minuut uit; die verwijdert aanvragen en bijlagen na twaalf maanden. Laat `MAATATELIER_CANONICAL_URL` in productie op `https://maatatelier.be` staan. Vul na verificatie van het domein `GOOGLE_SITE_VERIFICATION` en `BING_SITE_VERIFICATION` in.

Uploads zijn beperkt tot vijf bestanden van maximaal 15 MB per bestand. Configureer PHP met minstens `upload_max_filesize=15M` en `post_max_size=80M`, en geef de webserver eveneens een requestlimiet van minstens 80 MB.

De publieke crawlerbestanden staan op `/robots.txt`, `/sitemap.xml` en het experimentele `/llms.txt`. Dien de sitemap na livegang in bij Google Search Console en Bing Webmaster Tools. Alleen de productieomgeving krijgt indexeerbare robotsmetadata; lokale en testomgevingen krijgen automatisch `noindex, nofollow`.

## Controle

```bash
vendor/bin/pint --test
php artisan test
npm run build
```
