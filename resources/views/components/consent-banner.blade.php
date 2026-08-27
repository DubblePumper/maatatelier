<section class="fixed inset-x-0 bottom-0 z-50 p-3 sm:p-5" aria-label="Cookievoorkeuren" data-consent-banner hidden>
    <div class="mx-auto grid max-w-5xl gap-5 rounded-[1.75rem] border border-olive bg-ivory p-5 shadow-[0_-1rem_4rem_rgb(34_34_34/0.14)] sm:grid-cols-[1fr_auto] sm:items-end sm:p-7">
        <div>
            <p class="section-label">Jouw privacykeuze</p>
            <h2 class="mt-3 font-brand text-xl font-semibold" data-consent-heading tabindex="-1">Mogen we het websitebezoek meten?</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-anthracite/70" id="consent-description">
                Met jouw toestemming gebruikt MAATATELIER Google Analytics om te begrijpen welke pagina’s helpen. Zonder toestemming plaatsen we geen Analytics-cookies; Google ontvangt dan alleen beperkte, cookieloze meetsignalen en de website blijft volledig werken.
                <a class="font-semibold underline decoration-olive decoration-2 underline-offset-4" href="{{ route('cookies') }}">Lees het cookiebeleid</a>.
            </p>
        </div>
        <div class="grid gap-2 sm:min-w-52" aria-describedby="consent-description">
            <button class="secondary-button w-full" type="button" data-consent-deny>Niet toestaan</button>
            <button class="primary-button w-full" type="button" data-consent-accept>Analytics toestaan</button>
        </div>
    </div>
</section>

<p class="sr-only" aria-live="polite" data-consent-status></p>
