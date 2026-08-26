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

Nieuwe aanvragen gaan standaard naar `info@maatatelier.be`; dit adres is ook de publieke afzender en het contactadres. De meegeleverde SMTP-configuratie gebruikt de door het TLS-certificaat gedekte mailhost `web01.mc-node.net` op poort 587 met STARTTLS. Zet het mailboxwachtwoord uitsluitend als `MAIL_PASSWORD` in de niet-gecommitte `.env` van iedere omgeving. E-mails worden tijdens de aanvraag meteen verzonden en vereisen geen queue worker. Voer Laravel's scheduler iedere minuut uit; die verwijdert aanvragen en bijlagen na twaalf maanden. Laat `MAATATELIER_CANONICAL_URL` in productie op `https://maatatelier.be` staan. Vul na verificatie van het domein `GOOGLE_SITE_VERIFICATION` en `BING_SITE_VERIFICATION` in. Google Analytics gebruikt standaard meet-ID `G-7HHM0CZN91`; dit kan via `GOOGLE_ANALYTICS_MEASUREMENT_ID` worden gewijzigd.

Uploads zijn beperkt tot vijf bestanden van maximaal 15 MB per bestand. Configureer PHP met minstens `upload_max_filesize=15M` en `post_max_size=80M`, en geef de webserver eveneens een requestlimiet van minstens 80 MB.

De publieke crawler- en transparantiebestanden staan op `/robots.txt`, `/sitemap.xml`, `/llms.txt`, `/llms-full.txt`, `/humans.txt` en `/.well-known/security.txt`. Dien de sitemap na livegang in bij Google Search Console en Bing Webmaster Tools. Alleen de productieomgeving krijgt indexeerbare robotsmetadata en de Analytics-tag; lokale en testomgevingen krijgen automatisch `noindex, nofollow` en laden geen Analytics.

Meld na een inhoudelijke productie-update alle canonieke pagina’s bij IndexNow met `php artisan search:submit-index-now`. Geef desgewenst alleen gewijzigde paden mee, bijvoorbeeld `php artisan search:submit-index-now /maatwerk /prijzen`.

## Controle

```bash
vendor/bin/pint --test
php artisan test
npm run build
```
