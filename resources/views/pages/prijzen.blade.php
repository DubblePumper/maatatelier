@php($copy = trans('pages.prijzen'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto max-w-[94rem] px-5 pt-24 sm:px-8 lg:px-10 lg:pt-32" aria-labelledby="live-richtprijs">
        <div class="grid gap-12 rounded-[2.5rem] bg-sand p-7 sm:p-10 lg:grid-cols-[0.8fr_1.2fr] lg:p-14">
            <div>
                <p class="section-label">{{ $copy['calculation']['label'] }}</p>
                <h2 class="section-title" id="live-richtprijs">{{ $copy['calculation']['title'] }}</h2>
                <a class="primary-button mt-8" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['calculation']['cta'] }}</a>
            </div>
            <div>
                <ol class="border-t border-anthracite/20">
                    @foreach ($copy['calculation']['steps'] as $index => $step)
                        <li class="grid gap-3 border-b border-anthracite/20 py-6 sm:grid-cols-[2.5rem_1fr]">
                            <span class="font-brand text-xs font-semibold text-olive" aria-hidden="true">0{{ $index + 1 }}</span>
                            <div>
                                <h3 class="font-brand text-lg font-semibold">{{ $step['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-anthracite/70">{{ $step['copy'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>

                <div class="mt-7 rounded-2xl bg-ivory p-5">
                    <h3 class="font-brand font-semibold">{{ trans('pages.prijzen.calculation.benchmark_title', ['date' => trans('quote.configurator.benchmark_checked_at')]) }}</h3>
                    <p class="mt-2 text-sm leading-6 text-anthracite/70">{{ $copy['calculation']['benchmark_copy'] }}</p>
                    <ul class="mt-4 grid gap-3 text-sm">
                        @foreach (config('configurator.benchmark_sources') as $source)
                            <li>
                                <a class="font-semibold underline decoration-olive decoration-2 underline-offset-4 hover:text-olive" href="{{ $source['url'] }}">{{ $source['name'] }}</a>
                                <span class="block text-anthracite/65">{{ trans('quote.configurator.benchmark_sources.'.strtolower($source['name'])) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($copy['factors'] as $index => $factor)
                <article class="flex min-h-72 flex-col justify-between rounded-[2.5rem] border border-taupe/45 {{ $index % 2 === 0 ? 'bg-sand' : 'bg-ivory' }} p-7 text-anthracite sm:p-9">
                    <span class="font-brand text-xs font-semibold text-anthracite">0{{ $index + 1 }}</span>
                    <div>
                        <h2 class="font-brand text-2xl font-semibold tracking-[-0.03em]">{{ $factor['title'] }}</h2>
                        <p class="mt-3 text-sm leading-6 opacity-65">{{ $factor['copy'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-olive py-24 text-ivory">
        <div class="mx-auto grid max-w-[94rem] gap-12 px-5 sm:px-8 lg:grid-cols-2 lg:items-center lg:px-10">
            <div>
                <p class="section-label text-ivory">{{ $copy['proposal']['label'] }}</p>
                <h2 class="section-title text-ivory">{{ $copy['proposal']['title'] }}</h2>
            </div>
            <ul class="border-t border-ivory/30 text-sm leading-6 text-ivory">
                @foreach ($copy['proposal']['items'] as $item)
                    <li class="flex gap-3 border-b border-ivory/20 py-4"><span class="mt-2 size-1.5 shrink-0 rounded-full bg-oak" aria-hidden="true"></span>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-24 text-center sm:px-8 lg:py-32">
        <p class="section-label">{{ $copy['project']['label'] }}</p>
        <h2 class="section-title mx-auto">{{ $copy['project']['title'] }}</h2>
        <p class="mx-auto mt-6 max-w-2xl leading-7 text-anthracite/70">{{ $copy['project']['copy'] }}</p>
        <a class="primary-button mt-8" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['project']['cta'] }}</a>
    </section>
</x-layouts.app>
