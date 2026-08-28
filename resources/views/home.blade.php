@php($copy = trans('pages.home'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <section class="bg-ivory">
        <div class="mx-auto grid min-h-[calc(100svh-5rem)] max-w-[94rem] gap-10 px-5 py-10 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center lg:px-10 lg:py-14">
            <div class="relative z-10 py-6 lg:py-12">
                <div class="flex items-center gap-4">
                    <span class="h-px w-10 bg-olive" aria-hidden="true"></span>
                    <p class="section-label">{{ $copy['hero']['eyebrow'] }}</p>
                </div>
                <h1 class="mt-8 max-w-3xl font-brand text-[clamp(3.4rem,7vw,7.2rem)] font-semibold leading-[0.93] tracking-[-0.065em] text-anthracite">
                    {{ $copy['hero']['title_line_one'] }}<br>
                    <span class="display-italic font-normal text-olive">{{ $copy['hero']['title_emphasis'] }}</span> {{ $copy['hero']['title_line_two'] }}
                </h1>
                <p class="mt-8 max-w-xl text-lg leading-8 text-anthracite/70">{{ $copy['hero']['copy'] }}</p>
                <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                    <a class="primary-button" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['hero']['primary'] }} <span class="ml-2" aria-hidden="true">→</span></a>
                    <a class="secondary-button" href="{{ \App\Support\LocalizedRoute::url('maatwerk') }}">{{ $copy['hero']['secondary'] }}</a>
                </div>
                <ul class="mt-9 flex flex-wrap gap-x-7 gap-y-3 text-sm text-anthracite/65" aria-label="{{ $copy['hero']['benefits_aria'] }}">
                    @foreach ($copy['hero']['benefits'] as $benefit)
                        <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-olive" aria-hidden="true"></span>{{ $benefit }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="relative min-h-[30rem] sm:min-h-[42rem] lg:min-h-[calc(100svh-8rem)]">
                <div class="absolute inset-0 overflow-hidden rounded-t-[8rem] rounded-br-[2rem] bg-sand sm:rounded-t-[13rem]">
                    <img src="{{ asset('images/hero-interior-v2.webp') }}" width="1536" height="1024" alt="{{ $copy['hero']['image_alt'] }}" class="size-full object-cover object-center" fetchpriority="high" decoding="async">
                </div>
                <div class="absolute bottom-5 right-5 max-w-56 rounded-2xl bg-ivory/95 p-4 text-anthracite shadow-sm">
                    <p class="section-label">{{ $copy['hero']['concept_label'] }}</p>
                    <p class="mt-2 text-xs leading-5 text-anthracite/65">{{ $copy['hero']['concept_copy'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-taupe/40 bg-sand">
        <div class="mx-auto grid max-w-7xl gap-px bg-taupe/50 sm:grid-cols-3">
            @foreach ($copy['services'] as $service)
                <article class="bg-sand px-6 py-8 sm:px-8">
                    <span class="section-label">{{ $service['number'] }}</span>
                    <h2 class="mt-4 font-brand text-xl font-semibold"><a class="hover:underline" href="{{ \App\Support\LocalizedRoute::url('maatwerk') }}#{{ $service['anchor'] }}">{{ $service['title'] }}</a></h2>
                    <p class="mt-2 text-sm leading-6 text-anthracite/65">{{ $service['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-8 lg:px-10 lg:py-28">
        <div class="grid gap-14 lg:grid-cols-[0.75fr_1.25fr]">
            <div>
                <p class="section-label">{{ $copy['promise']['label'] }}</p>
                <h2 class="section-title">{{ $copy['promise']['title'] }}</h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($copy['promise']['cards'] as $card)
                    <article class="card-surface">
                        <h3 class="font-brand text-xl font-semibold">{{ $card['title'] }}</h3>
                        <p class="mt-3 text-sm leading-6 text-anthracite/65">{{ $card['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-anthracite py-20 text-ivory lg:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:px-10">
            <div>
                <p class="font-brand text-[0.68rem] font-semibold uppercase tracking-[0.24em] text-oak">{{ $copy['configurator']['label'] }}</p>
                <h2 class="mt-5 max-w-2xl font-brand text-4xl font-semibold leading-[1.02] tracking-[-0.045em] sm:text-6xl">{{ $copy['configurator']['title'] }}</h2>
                <p class="mt-6 max-w-xl text-lg leading-8 text-ivory/70">{{ $copy['configurator']['copy'] }}</p>
                <a class="mt-8 inline-flex min-h-12 items-center justify-center rounded-full bg-oak px-6 py-3.5 font-brand text-sm font-semibold text-anthracite hover:bg-sand" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['configurator']['cta'] }} <span aria-hidden="true">→</span></a>
            </div>
            <div class="rounded-[2rem] bg-sand p-6 text-anthracite sm:p-9" aria-hidden="true">
                <div class="flex h-64 gap-2 rounded-xl border-8 border-oak bg-ivory p-2">
                    @foreach ([1, 2, 3] as $module)
                        <div class="flex-1 border border-taupe bg-oak/75 p-2">
                            <div class="mt-1 h-px bg-anthracite/30"></div>
                            <div class="mt-16 h-px bg-anthracite/30"></div>
                            <span class="mx-auto mt-20 block size-1.5 rounded-full bg-anthracite/60"></span>
                        </div>
                    @endforeach
                </div>
                <p class="mt-5 font-brand text-sm font-semibold">{{ $copy['configurator']['preview'] }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-20 sm:px-8 lg:px-10 lg:py-28">
        <div class="grid gap-12 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="section-label">{{ $copy['process']['label'] }}</p>
                <h2 class="section-title">{{ $copy['process']['title'] }}</h2>
            </div>
            <ol class="divide-y divide-taupe/50 border-y border-taupe/50">
                @foreach ($copy['process']['steps'] as $step)
                    <li class="grid gap-3 py-6 sm:grid-cols-[3rem_12rem_1fr] sm:items-baseline">
                        <span class="section-label">{{ $step['number'] }}</span>
                        <h3 class="font-brand font-semibold">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-6 text-anthracite/65">{{ $step['copy'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="bg-sand py-20 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_auto] lg:items-end lg:px-10">
            <div>
                <p class="section-label">{{ $copy['pricing']['label'] }}</p>
                <h2 class="mt-5 max-w-3xl font-brand text-4xl font-semibold leading-[1.02] tracking-[-0.045em] sm:text-6xl">{{ $copy['pricing']['title'] }}</h2>
                <p class="mt-6 max-w-2xl leading-7 text-anthracite/65">{{ trans('pages.home.pricing.copy', ['date' => trans('quote.configurator.benchmark_checked_at')]) }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                <a class="primary-button" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['pricing']['primary'] }}</a>
                <a class="secondary-button" href="{{ \App\Support\LocalizedRoute::url('prijzen') }}">{{ $copy['pricing']['secondary'] }}</a>
            </div>
        </div>
    </section>
</x-layouts.app>
