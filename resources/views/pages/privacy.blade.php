<x-layouts.app title="Privacy | MAATATELIER" description="Lees hoe MAATATELIER persoonsgegevens en bestanden uit een maatwerkaanvraag verwerkt en beschermt.">
    <x-page-header eyebrow="Privacy" title="Zorgvuldig met jouw gegevens en beelden." intro="We verzamelen alleen informatie die nodig is om je maatwerkaanvraag te beoordelen en te beantwoorden." />

    <article class="prose-brand mx-auto max-w-3xl px-5 py-20 sm:px-8 lg:py-28">
        <h2>Welke gegevens we verwerken</h2>
        <p>Bij een aanvraag verwerken we je naam, e-mailadres, telefoonnummer, postcode, projectkeuzes, globale maten, toelichting en eventuele foto's of schetsen die je zelf toevoegt.</p>

        <h2>Waarom we deze gegevens gebruiken</h2>
        <p>We gebruiken deze informatie uitsluitend om je vraag te beoordelen, contact met je op te nemen, een voorstel voor te bereiden en de aanvraag administratief op te volgen.</p>

        <h2>Bestanden en beveiliging</h2>
        <p>Geüploade bestanden worden privé opgeslagen en zijn niet openbaar bereikbaar. MAATATELIER ontvangt per aanvraag tijdelijk ondertekende downloadlinks die na {{ config('maatatelier.attachment_link_lifetime_days') }} dagen vervallen. Toegang is beperkt tot wat nodig is voor de behandeling van je aanvraag.</p>

        <h2>Bewaartermijn</h2>
        <p>Aanvragen die niet tot een opdracht leiden, worden uiterlijk twaalf maanden na ontvangst verwijderd, samen met de bijbehorende uploads. Wettelijke bewaarplichten kunnen voor klant- en factuurgegevens een langere termijn vereisen.</p>

        <h2>Delen met anderen</h2>
        <p>We verkopen je gegevens niet. Technische dienstverleners ontvangen alleen toegang wanneer dat nodig is voor hosting, opslag of e-mail en moeten de gegevens passend beschermen.</p>

        <h2>Websiteanalyse en cookies</h2>
        <p>De Google-tag start met opslag standaard geweigerd. Zonder toestemming worden geen Analytics-cookies geplaatst en ontvangt Google alleen beperkte, cookieloze meetsignalen. Volledige Analytics-meting start pas nadat je Analytics actief toestaat; advertentiepersonalisatie blijft uitgeschakeld. Welke opslag wordt gebruikt en hoe je jouw keuze wijzigt, staat in het <a href="{{ route('cookies') }}">cookiebeleid</a>.</p>

        <h2>Jouw rechten</h2>
        <p>Je kunt vragen om inzage, correctie of verwijdering van je persoonsgegevens. Mail daarvoor naar <a href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a> en vermeld dat je vraag over privacy gaat. Identiteitscontrole kan nodig zijn om gegevens veilig te houden.</p>
    </article>
</x-layouts.app>
