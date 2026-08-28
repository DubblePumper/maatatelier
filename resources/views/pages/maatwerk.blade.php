@php($copy = trans('pages.maatwerk'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto max-w-[94rem] px-5 py-24 sm:px-8 lg:px-10 lg:py-32">
        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($copy['items'] as $index => $item)
                <article id="{{ $item['anchor'] }}" class="relative min-h-[30rem] scroll-mt-8 overflow-hidden rounded-[2.5rem] border border-taupe/45 {{ $index % 2 === 0 ? 'bg-sand' : 'bg-ivory' }} p-7 text-anthracite sm:p-10">
                    <span class="font-brand text-xs font-semibold uppercase tracking-[0.2em] opacity-70">0{{ $index + 1 }}</span>
                    <h2 class="mt-16 max-w-lg font-brand text-4xl font-semibold leading-none tracking-[-0.045em] sm:text-5xl">{{ $item['title'] }}</h2>
                    <p class="mt-5 max-w-xl text-base leading-7 opacity-70">{{ $item['copy'] }}</p>
                    <ul class="absolute inset-x-7 bottom-7 flex flex-wrap gap-2 text-xs sm:inset-x-10 sm:bottom-10">
                        @foreach ($item['features'] as $feature)
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
                <p class="section-label">{{ $copy['multiple']['label'] }}</p>
                <h2 class="section-title">{{ $copy['multiple']['title'] }}</h2>
            </div>
            <div>
                <p class="text-lg leading-8 text-anthracite/70">{{ $copy['multiple']['copy'] }}</p>
                <a class="primary-button mt-7" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['multiple']['cta'] }}</a>
            </div>
        </div>
    </section>
</x-layouts.app>
