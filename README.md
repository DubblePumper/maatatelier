# MAATATELIER

Publieke Laravel-website voor maatatelier.be, met informatie over maatkasten, keukens en interieur en een toegankelijke offerteflow met privé opgeslagen bijlagen.

## Lokaal starten

Vereisten: PHP 8.3, Composer 2, Node.js en npm.

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

Laat de domeinroot naar `public/` wijzen en gebruik PHP 8.3. Zet vóór de eerste publieke request minstens deze waarden in de niet-gecommitte `.env` op de server:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://maatatelier.be
LOG_LEVEL=warning

SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=sync

MAATATELIER_CANONICAL_URL=https://maatatelier.be
MAATATELIER_PRODUCTION_HOSTS=maatatelier.be,www.maatatelier.be
GOOGLE_ANALYTICS_MEASUREMENT_ID=G-7HHM0CZN91
```

Gebruik een unieke `APP_KEY`, vul de echte database- en SMTP-gegevens uitsluitend op de server in en houd `storage/` en `bootstrap/cache/` schrijfbaar voor de PHP-gebruiker. De applicatie herkent het canonieke domein ook als productieverzoek wanneer `APP_ENV` per ongeluk fout staat, zodat indexering, Analytics-contract en security headers niet stilvallen. Dit vangnet vervangt de correcte productie-instellingen niet: met name `APP_DEBUG=false` blijft verplicht.

Nieuwe aanvragen gaan standaard naar `info@maatatelier.be`; dit adres is ook de publieke afzender en het contactadres. De meegeleverde SMTP-configuratie gebruikt de door het TLS-certificaat gedekte mailhost `web01.mc-node.net` op poort 587 met STARTTLS. Zet het mailboxwachtwoord uitsluitend als `MAIL_PASSWORD` in de niet-gecommitte `.env` van iedere omgeving. E-mails worden tijdens de aanvraag meteen verzonden en vereisen geen queue worker. Voer Laravel's scheduler iedere minuut uit; die verwijdert aanvragen en bijlagen na twaalf maanden. Laat `MAATATELIER_CANONICAL_URL` in productie op `https://maatatelier.be` staan. Vul na verificatie van het domein `GOOGLE_SITE_VERIFICATION` en `BING_SITE_VERIFICATION` in. Google Analytics gebruikt standaard meet-ID `G-7HHM0CZN91`; dit kan via `GOOGLE_ANALYTICS_MEASUREMENT_ID` worden gewijzigd.

Analytics gebruikt de privacyvriendelijke basisvariant van Google Consent Mode: de externe Google-tag wordt pas geladen nadat iemand “Analytics toestaan” kiest. Test daarom in Tag Assistant door op de toestemmingsbanner te accepteren; een automatische detector die geen toestemming geeft kan de tag bewust niet zien.

Uploads zijn beperkt tot vijf bestanden van maximaal 15 MB per bestand. `public/.user.ini` configureert PHP hiervoor met een totale requestlimiet van 80 MB; controleer of de hosting `.user.ini` ondersteunt en geef de webserver eveneens een requestlimiet van minstens 80 MB. Foto's en schetsen blijven privé opgeslagen. De interne aanvraagmail bevat ondertekende downloadlinks die standaard na 90 dagen vervallen, zodat ook grote aanvragen zonder zware e-mailbijlagen aankomen.

De publieke crawler- en transparantiebestanden staan op `/robots.txt`, `/sitemap.xml`, `/llms.txt`, `/llms-full.txt`, `/humans.txt` en `/.well-known/security.txt`. Dien de sitemap na livegang in bij Google Search Console en Bing Webmaster Tools. Alleen productie en de geconfigureerde productiedomeinen krijgen indexeerbare robotsmetadata en het consent-gestuurde Analytics-contract; lokale en testhosts krijgen automatisch `noindex, nofollow` en laden geen Analytics.

Installeer dependencies en bouw assets vanuit de lockfiles. Voer daarna migraties en Laravel's productiecache uit:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

Laat op shared hosting elke minuut `php artisan schedule:run` uitvoeren. Een queue worker is niet nodig en mag niet als vereiste in het hostingpaneel worden ingesteld.

Meld na een inhoudelijke productie-update alle canonieke pagina’s bij IndexNow met `php artisan search:submit-index-now`. Geef desgewenst alleen gewijzigde paden mee, bijvoorbeeld `php artisan search:submit-index-now /maatwerk /prijzen`.

## Controle

```bash
vendor/bin/pint --test
php artisan test
npm run build
```
