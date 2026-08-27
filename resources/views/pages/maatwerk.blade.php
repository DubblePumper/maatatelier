<x-layouts.app title="Maatkasten, dressings en keukens op maat | Ronse" description="Ontdek maatkasten, dressings, keukens, tv-meubels, bureaus en complete interieurs van MAATATELIER in Ronse en ruime omgeving.">
    <x-page-header eyebrow="Ons maatwerk" title="Maatkasten, dressings en keukens op maat." intro="We ontwerpen rond gebruik, verhoudingen en materiaal. Van één slimme kast tot een samenhangend interieur." />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ([
                ['maatkasten', 'Maatkasten', 'Van een rustige wandkast tot slimme opbergruimte onder een hellend dak. De indeling volgt wat jij wilt bewaren en hoe je de ruimte gebruikt.', ['Nissen en schuine wanden', 'Open en gesloten vakken', 'Verlichting en kabelbeheer']],
                ['dressings', 'Dressings', 'Een overzichtelijke dressing waarin kleding, schoenen en accessoires elk hun plaats krijgen. Open, gesloten of een combinatie van beide.', ['Laden en kledingroedes', 'Schoenen- en accessoirevakken', 'Warme, gerichte verlichting']],
                ['keukens', 'Keukens', 'Een keuken op maat vertrekt bij jouw dagelijkse bewegingen. We brengen werkzones, opbergruimte, materiaal en toestellen samen in één rustig geheel.', ['Logische werkzones', 'Duurzame oppervlakken', 'Integratie van toestellen']],
                ['tv-meubels', 'TV- en wandmeubels', 'Techniek krijgt een vaste plaats zonder de ruimte te overheersen. Kabels, apparatuur, boeken en objecten worden één doordacht wandbeeld.', ['Onzichtbaar kabelbeheer', 'Open en gesloten opberging', 'Afwerking van wand tot wand']],
                ['bureaus', 'Bureaus en thuiskantoren', 'Een werkplek die visueel bij je interieur hoort en tegelijk ergonomisch, rustig en praktisch blijft.', ['Werkblad op ideale hoogte', 'Opberging binnen handbereik', 'Verlichting en stroompunten']],
                ['bijkeukens', 'Bijkeukens en ander maatwerk', 'Ook technische of compacte ruimtes verdienen een heldere indeling. We zoeken naar maximale functie met minimale visuele drukte.', ['Was- en voorraadruimte', 'Ingebouwde toestellen', 'Maatwerk voor moeilijke hoeken']],
            ] as $index => [$anchor, $title, $copy, $features])
                <article id="{{ $anchor }}" class="relative min-h-[30rem] scroll-mt-8 overflow-hidden rounded-[2.5rem] border border-taupe/45 {{ $index % 2 === 0 ? 'bg-sand' : 'bg-ivory' }} p-7 text-anthracite sm:p-10">
                    <span class="font-brand text-xs font-semibold uppercase tracking-[0.2em] opacity-70">0{{ $index + 1 }}</span>
                    <h2 class="mt-16 max-w-lg font-brand text-4xl font-semibold leading-none tracking-[-0.045em] sm:text-5xl">{{ $title }}</h2>
                    <p class="mt-5 max-w-xl text-base leading-7 opacity-70">{{ $copy }}</p>
                    <ul class="absolute inset-x-7 bottom-7 flex flex-wrap gap-2 text-xs sm:inset-x-10 sm:bottom-10">
                        @foreach ($features as $feature)
                            <li class="rounded-full border border-current/25 px-3 py-2">{{ $feature }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-sand py-24">
        <div class="mx-auto grid max-w-[94rem] gap-10 px-5 sm:px-8 lg:grid-cols-2 lg:items-end lg:px-10">
            <div>
                <p class="section-label">Meerdere ruimtes</p>
                <h2 class="section-title">Eén lijn door je hele interieur.</h2>
            </div>
            <div>
                <p class="text-lg leading-8 text-anthracite/70">Wil je keuken, leefruimte en opberging op elkaar afstemmen? Dan bekijken we kleuren, materialen, verhoudingen en details als één totaalontwerp.</p>
                <a class="primary-button mt-7" href="{{ route('quote_requests.create') }}">Start configurator</a>
            </div>
        </div>
    </section>
</x-layouts.app>
