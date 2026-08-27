<x-layouts.app title="Cookiebeleid | MAATATELIER" description="Lees welke noodzakelijke opslag en analytische cookies MAATATELIER gebruikt, waarom en hoe je jouw keuze kunt wijzigen.">
    <x-page-header eyebrow="Cookies" title="Duidelijke keuzes, zonder verborgen tracking." intro="De website werkt zonder analytische cookies. De Google-tag start met geweigerde toestemming en gebruikt pas na jouw keuze Analytics-cookies." />

    <article class="prose-brand mx-auto max-w-3xl px-5 py-20 sm:px-8 lg:py-28">
        <h2>Noodzakelijke lokale opslag</h2>
        <p>We bewaren je keuze voor Analytics lokaal in je browser onder <code>maatatelier_consent_v1</code>. Zo hoeven we de vraag niet bij elk bezoek opnieuw te stellen. Deze voorkeur bevat geen naam, e-mailadres of andere contactgegevens, wordt niet naar Google verzonden en blijft staan tot je de keuze wijzigt of de browseropslag wist.</p>

        <h2>Google Analytics</h2>
        <p>De Google-tag voor Analytics 4 met meet-ID <code>G-7HHM0CZN91</code> wordt op elke pagina geladen met alle opslag- en advertentiesignalen standaard geweigerd. Zolang je geen toestemming geeft, plaatst Analytics geen cookies en ontvangt Google alleen beperkte, cookieloze toestemmings- en meetsignalen. Na toestemming meten we onder meer paginaweergaven, sessies, het globale apparaattype en de globale herkomst van bezoeken. We sturen geen namen, e-mailadressen, telefoonnummers, geüploade bestanden of referentienummers naar Analytics.</p>

        <h2>Cookies na toestemming</h2>
        <p>Google Analytics kan de cookies <code>_ga</code> en <code>_ga_7HHM0CZN91</code> plaatsen om bezoekers en sessies van elkaar te onderscheiden. MAATATELIER beperkt hun levensduur tot maximaal 180 dagen vanaf de eerste plaatsing en verlengt die termijn niet automatisch bij elk paginabezoek.</p>

        <h2>Geen advertentiepersonalisatie</h2>
        <p>Advertentieopslag, advertentiegebruikersdata, advertentiepersonalisatie en Google Signals blijven uitgeschakeld. Alleen <code>analytics_storage</code> wordt na jouw actieve toestemming op toegestaan gezet.</p>

        <h2>Je keuze wijzigen</h2>
        <p>Gebruik op elke pagina de knop “Cookie-instellingen” in de footer. Als je Analytics later weigert, sturen we onmiddellijk een ingetrokken toestemmingssignaal en verwijderen we bereikbare Analytics-cookies uit je browser.</p>

        <h2>Bewaring bij Google</h2>
        <p>De bewaartermijn van gebeurtenissen en gebruikersgegevens op de servers van Google wordt beheerd in het Google Analytics-account. Voor vragen over die instelling of over jouw privacyrechten kun je mailen naar <a href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>.</p>
    </article>
</x-layouts.app>
