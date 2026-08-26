<x-layouts.app title="Interieur op maat: inspiratie en materialen | MAATATELIER" description="Ontdek de warme interieurstijl van MAATATELIER met eik, natuursteen, ivoor en olijfbrons. Conceptbeelden voor tijdloos maatwerk.">
    <x-page-header eyebrow="Inspiratie" title="Rust hoeft niet braaf te zijn." intro="Een eerste sfeerbeeld om jouw voorkeuren te ontdekken. Dit concept toont onze visuele richting; echte realisaties worden later toegevoegd." />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <figure class="relative overflow-hidden rounded-t-[10rem] rounded-br-[2.5rem] bg-anthracite sm:rounded-t-[16rem]">
            <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="Conceptbeeld van een warm maatwerkinterieur met eik, natuursteen en olijfgroen" class="aspect-[4/5] w-full object-cover sm:aspect-[16/9]" fetchpriority="high" decoding="async">
            <figcaption class="absolute bottom-5 left-5 max-w-sm rounded-2xl bg-anthracite/85 p-5 text-ivory backdrop-blur-md sm:bottom-8 sm:left-8">
                <p class="font-brand text-[0.62rem] font-semibold uppercase tracking-[0.18em] text-oak">Concept 01 · Warm contrast</p>
                <p class="mt-2 text-sm leading-6 text-ivory/70">Eik en steen krijgen meer diepte naast rustig olijfbrons.</p>
            </figcaption>
        </figure>
    </section>

    <section class="bg-anthracite py-24 text-ivory lg:py-32">
        <div class="mx-auto max-w-[94rem] px-5 sm:px-8 lg:px-10">
            <div class="grid gap-12 lg:grid-cols-[0.55fr_1.45fr]">
                <p class="font-brand text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-oak">Onze ingrediënten</p>
                <h2 class="max-w-5xl font-brand text-5xl font-semibold leading-[0.93] tracking-[-0.055em] sm:text-7xl">Natuurlijk. Tactiel. Met één <span class="display-italic font-normal text-oak">twist.</span></h2>
            </div>
            <div class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['bg-oak text-anthracite', 'Eik', 'warm, levendig, eerlijk'],
                    ['bg-ivory text-anthracite', 'Ivoor', 'licht zonder klinisch te worden'],
                    ['bg-olive text-ivory', 'Olijfbrons', 'diepte en architecturale rust'],
                    ['bg-sand text-anthracite', 'Zand', 'zacht contrast en warmte'],
                ] as [$classes, $title, $copy])
                    <article class="{{ $classes }} flex min-h-64 flex-col justify-between rounded-[2.5rem] p-7">
                        <span class="size-10 rounded-full border border-current/30" aria-hidden="true"></span>
                        <div>
                            <h3 class="font-brand text-2xl font-semibold">{{ $title }}</h3>
                            <p class="mt-2 text-sm">{{ $copy }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-10 md:grid-cols-3">
            @foreach ([['01', 'Licht', 'Daglicht waar het kan, gerichte verlichting waar het helpt.'], ['02', 'Ritme', 'Herhaling en verhouding brengen rust in grote en kleine ruimtes.'], ['03', 'Detail', 'Een fijne aansluiting of verrassende kleur maakt het ontwerp persoonlijk.']] as [$number, $title, $copy])
                <article class="border-t border-anthracite/25 pt-6">
                    <span class="font-brand text-xs font-semibold text-olive">{{ $number }}</span>
                    <h2 class="mt-10 font-brand text-3xl font-semibold tracking-[-0.035em]">{{ $title }}</h2>
                    <p class="mt-4 max-w-sm leading-7 text-anthracite/70">{{ $copy }}</p>
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>
