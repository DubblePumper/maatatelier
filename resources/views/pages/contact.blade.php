@php($copy = trans('pages.contact'))

<x-layouts.app :title="$copy['meta_title']" :description="$copy['meta_description']">
    <x-page-header :eyebrow="$copy['header']['eyebrow']" :title="$copy['header']['title']" :intro="$copy['header']['intro']" />

    <section class="mx-auto grid max-w-[94rem] gap-4 px-5 py-24 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:px-10 lg:py-32">
        <div class="relative min-h-[32rem] overflow-hidden rounded-[2.5rem] bg-olive p-8 text-ivory sm:p-12">
            <div class="absolute -bottom-28 -right-28 size-96 rounded-full border-[4rem] border-oak/25" aria-hidden="true"></div>
            <p class="relative font-brand text-xs font-semibold uppercase tracking-[0.2em] text-ivory">{{ $copy['configurator']['label'] }}</p>
            <h2 class="relative mt-8 max-w-xl font-brand text-4xl font-semibold leading-[0.95] tracking-[-0.05em] sm:text-6xl">{{ $copy['configurator']['title'] }}</h2>
            <p class="mt-5 max-w-xl leading-7 text-ivory">{{ $copy['configurator']['copy'] }}</p>
            <a class="relative mt-8 inline-flex min-h-12 items-center justify-center rounded-full bg-ivory px-6 py-3.5 font-brand text-sm font-semibold text-olive hover:bg-anthracite hover:text-ivory" href="{{ \App\Support\LocalizedRoute::url('quote_requests.create') }}">{{ $copy['configurator']['cta'] }} <span aria-hidden="true">→</span></a>
        </div>
        <aside class="rounded-[2.5rem] bg-sand p-8 sm:p-10">
            <h2 class="font-brand text-xl font-semibold">{{ $copy['region']['title'] }}</h2>
            <p class="mt-4 leading-7 text-anthracite/70">{{ $copy['region']['copy'] }}</p>
            <h2 class="mt-14 border-t border-anthracite/20 pt-8 font-brand text-xl font-semibold">{{ $copy['next']['title'] }}</h2>
            <p class="mt-4 leading-7 text-anthracite/70">{{ $copy['next']['copy'] }}</p>
            <h2 class="mt-14 border-t border-anthracite/20 pt-8 font-brand text-xl font-semibold">{{ $copy['email'] }}</h2>
            <a class="mt-4 inline-flex min-h-11 items-center break-all font-semibold text-anthracite underline decoration-olive decoration-2 underline-offset-4" href="mailto:{{ config('maatatelier.contact_email') }}">{{ config('maatatelier.contact_email') }}</a>
        </aside>
    </section>
</x-layouts.app>
